# 메타체험단 소스 최초 등록 · 보안 조치 (2026-07-06)

## 1. 최초 등록
- 라이브 사이트(/www) 소스를 파일질라로 내려받아 GitHub 최초 등록(main). `data/`(업로드 이미지 22GB+)는 제외.
- 코드 섹션이 검수: 총 19,020개 → 민감/벤더 제거 후 16,735개.

## 2. ⚠️ 노출된 자격증명 — 반드시 교체(rotation) 필요
최초 커밋(main)에 아래 실제 값이 포함되어 올라갔다. **저장소가 private이라 노출 범위는 협업자로 한정되지만, 원칙상 교체 권장.**
| 파일 | 내용 | 조치 |
|---|---|---|
| `config.php` | DB 접속(sql_host/user/password), INIpay 상점ID·signkey | **DB 비밀번호 변경 + INIpay signkey 재발급** 권장 |
| `AuthKey_4RMZ7YCS5H.p8` | 애플 인증 개인키(로그인/푸시) | **애플 개발자센터에서 키 revoke 후 재발급** 권장 |
| `mc_share_key.php` | 전산 연동 공유 HMAC 키 | 전산과 함께 키 교체 시 동기화 |

> 완전 제거하려면 main 최초 커밋 history 재작성(force-push)이 필요 — 운영자 승인 시 진행 가능. 미승인 시 최소한 위 값 교체로 실질 위험 제거.

## 3. 저장소 정리 완료 (이 브랜치)
- `.gitignore` 신설 — `config.php`, `*.p8`, `mc_share_key.php`, `editor/imageUpload/config.php`, `nfor_phpmyadmin/`, `.DS_Store` 추적 제외.
- `config.php` 추적 제거 + 값 없는 `config.sample.php`로 구조 보존.
- `nfor_phpmyadmin/`(2,270개) 저장소에서 제거 — 벤더 phpMyAdmin. **⚠️ 라이브 서버 `/www/nfor_phpmyadmin/`가 외부 접근 가능하면 별도 보안 위험**(경로 접근 차단 또는 관리자 IP 제한 권장).
- 애플 키·DS_Store 추적 제거.

## 4. 사이트 구조 분석 (Q1~Q4 결론)
- **Q1 광고주 연결: 이미 있음.** `nfor_member`(adv_ 접두=광고주) + `nfor_campaign.cp_supply_no`로 캠페인↔업체 연결. 등록 폼에 입점업체/광고주 선택칸(2026-06 신설).
- **Q2 리뷰 URL: 저장됨.** `nfor_review.re_url`, `re_cp_id`(캠페인 연결), `re_datetime`, `rv_step`(퍼널 1신청~4완료).
- **Q3 노출·유입 통계: 이미 수집·표시 중.** `campaign_report.php` — 조회수(cp_click), referer 유입 매체 분류, 상위 유입 출처(블로그/도메인), IP 방문/전환, 요일별. 캠페인 단위 공유 보고서(`campaign_report_share.php`)도 존재.
- **Q4 운영형태: 캠페인 연속 오픈.** 회차 개념은 명시적 없음 → 업체별 캠페인 시작일 순번으로 회차 부여. 병렬 캠페인 가능.

→ **결론: 이상적 구조(입점업체 등록→캠페인 연결→집계)가 이미 70% 구축됨.** 신규로 만들 것은 ① 광고주(입점업체)별 **여러 캠페인 누적 집계** ② 전산 연동용 **광고주별 집계 토큰 API**(기존 mc_cal_api 방식). 재료(re_url·cp_click·referer 유입·rv_step 퍼널)는 전부 존재.

## 5. 동시작업 주의
- 광고주/입점업체 선택칸·공유 보고서가 **2026-06월 최근 활발히 개발됨** → 다른 섹션이 메타체험단 광고주 리포팅을 동시 작업했을 가능성. 착수 전 열린 브랜치/PR 확인 필요.

## 6. 동시작업 — 확인 완료 (2026-07-06)
- 운영자 확인: **메타체험단은 운영자 지시로만 개발됨(단독)**. 2026-06월 광고주/입점업체 선택칸·공유 보고서 작업도 본 작업의 연장선. → 동시작업 충돌 우려 없음, 이 브랜치에서 계속 진행.
- 다음 단계: 광고주(입점업체)별 **다중 캠페인 누적 집계** + 전산 연동 **집계 토큰 API** 를 시안으로 설계 → 운영자 확정 → 구현(기존 기능 불변·추가만).

## 7. 광고주별 집계 API 설계 시안 (2026-07-06, 운영자 검토 대기)
정밀 코드 분석(Explore) 근거로 설계. 시안: `docs/advertiser-aggregate-api-design.html`.
- **신규 파일 1개**: `mc_advertiser_api.php` — 기존 `mc_cal_api.php` 골격 복제(`include_once "path.php"` + `$MC_ADV_TOKEN` GET 토큰 검증 + `json_encode(...,JSON_UNESCAPED_UNICODE)`). 기존 사이트 기능 불변.
- **2모드**: `?mode=advertisers`(광고주 목록 — 매핑 드롭다운용, `mb_admin='1' AND mb_id LIKE 'adv_%'`) / `?supply_no=N`(광고주별 누적: total + campaigns[] 회차별 + reviews[] 링크).
- **집계 규칙**: 캠페인 롤업 = `nfor_campaign.cp_supply_no = mb_no`. 신청/선정/리뷰 = `nfor_review.rv_step` 재계산(신청=전체, 선정=step2+3+4, 리뷰=step3+4, `rv_delete='0'`) — 부정확한 카운터 컬럼 미사용. 조회수=`cp_click` SUM. 리뷰URL=`rv_url`. 회차=캠페인 시작일 순번.
- **v1 제외**: 트래픽 세부(nfor_traffic_YYYY_WW 주 분할 → 캠페인 루프 필요, 무거움)는 다음 단계. v1은 조회수·퍼널까지.
- 전산측: `MetacrewAdvertiserClient`(기존 Metacrew*Client 동형) + 진행중 공유링크 설정에 1클릭 매핑 + ClientPortal 대시보드 JSON에 병합.

## 8. 광고주 집계 API 구현 (2026-07-06) — 운영자 결정 반영
파일: **mc_advertiser_api.php** (신규, 기존 mc_cal_api.php 패턴). 운영자 확정 옵션: 회차만 표기 / 리뷰 링크 전체 / 메타체험단 전 캠페인 / 유입·노출 1차 포함.
- mode=advertisers: 광고주 목록(mb_admin='1' AND mb_id LIKE 'adv_%', q 검색). campaign_count 포함.
- supply_no=N: total(누적) + campaigns[](회차별, rv_step 퍼널 + cp_click + 방문) + media[](유입매체 분류) + reviews[](rv_url 전체).
- 트래픽: 캠페인별 nfor_traffic_YYYY_WW(주차) 존재 확인(SHOW TABLES) 후 집계 → 없으면 skip(오류 방지).
- **토큰**: 하드코딩 안 함. 서버 전용 `mc_api_token.php`(.gitignore 등재)에서 `return '토큰';` 로드. 미설정 시 403.
- 검증: `php -l` 문법 통과.

### ⚠️ 추가 발견 — 기존 API 토큰도 노출됨
`mc_cal_api.php`(MC_CAL_TOKEN), `mc_point_bank_api.php`(MC_PB_TOKEN)에 토큰이 **하드코딩된 채 저장소에 올라감**. 신규 API는 파일 분리 방식으로 안전하나, 기존 2개는 여전히 노출. → 별도 작업으로 이 둘도 mc_api_token 방식으로 이전 + 토큰 교체 권장(전산 application.yml의 metacrew.calendar.token 동기 변경 필요).

### 배포(운영자)
1. 서버 /www 에 `mc_advertiser_api.php` 업로드(FTP).
2. 서버 /www 에 `mc_api_token.php` 생성: `<?php return '<랜덤토큰>';` (전달한 제안 토큰 사용 가능, 저장소엔 없음).
3. 전산 application.yml에 `metacrew.advertiser.url/token` 추가(같은 토큰) — 전산 BE 배선 시.
4. 확인: 브라우저에서 `/mc_advertiser_api.php?token=<토큰>&mode=advertisers` → 광고주 목록 JSON.
