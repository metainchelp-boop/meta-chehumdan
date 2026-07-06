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
