# CLAUDE.md — 메타체험단(meta-chehumdan) 작업 가이드 (필독)

> 새 세션은 작업 시작 전 이 파일을 먼저 읽는다.
> 이 저장소는 **라이브 운영 중인 체험단 사이트**(meta-chehumdan.com, PHP + MariaDB, 카페24 호스팅)의 소스다.
> 전산(metainc-web-*)과 **동일한 동시작업·산출물 규칙**을 따른다.

## ⚠️ 라이브 사이트 — 최우선 주의

1. **기존 기능 불변 · 추가만** — 운영 중인 사이트다. 기존 파일을 함부로 바꾸지 말고, 신규 기능은 새 파일/새 함수로 더한다. 불가피하게 기존 파일을 건드리면 영향 범위를 먼저 밝힌다.
2. **배포 = 저장소 자동반영 아님** — 실제 배포는 **FTP로 서버(/www)에 파일 업로드**다. 저장소 커밋은 **배포가 아니다**. 배포는 운영자 승인 후, 변경 파일 목록을 안내하는 수동 방식으로 한다(추후 GitHub Actions→FTP 자동화 검토).
3. **DB 스키마 변경 주의** — 라이브 DB에 `ALTER`/`CREATE`가 필요하면 반드시 멱등(`IF NOT EXISTS`, `ADD COLUMN IF NOT EXISTS`)으로 작성하고 운영자 확인 후 적용한다.

## 🔐 보안 (절대 규칙)

- **민감정보 커밋 금지** — DB 비밀번호·API 키·인증서·상점키는 저장소에 올리지 않는다. `.gitignore`에 등재된 파일(`config.php`, `*.p8`, `mc_share_key.php`, `editor/imageUpload/config.php`, `nfor_phpmyadmin/`)은 서버에만 존재한다.
- **구조는 `config.sample.php`** — 설정 구조가 필요하면 값 없는 샘플을 참조한다. 실제 값은 서버 `config.php`.
- **최초 소스 등록 시 노출된 자격증명은 반드시 교체(rotation)** — 상세는 `docs/META_CHEHUMDAN_SETUP.md` 참조.

## 산출물 전달 형식 (전산과 동일)

- 문서·리포트·시안은 **자체완결 HTML**(인라인 CSS, CDN 없음)로 만들어 크롬에서 바로 열람 가능하게 전달.
- **화면 있는 작업은 디자인 시안 항상 병행**, **전달본 파일명·`<title>`은 한글**로.
- **고정 라이브 시안 링크(Artifact)** — 전산 규칙과 동일(운영자 지시 2026-07-06): 화면 있는 개발 작업은 시안을 **Artifact 고정 URL**로 발행하고 그 시안 기반으로 개발한다. 개선 시 **같은 `file_path`(새 세션은 `url=<기존 주소>`)로 재발행 → 같은 URL 갱신**(새 URL 금지). 상단 업데이트 스탬프 + 하단 변경 이력 유지. 공유는 운영자가 claude.ai에서 **"링크가 있는 사람 누구나"** 로 설정. 작업별 고정 URL은 작업 문서에 기록.
- 커밋 메시지·코드·PR 본문에 **AI/모델 식별자 금지**. 한글 UTF-8.

## 아키텍처 요약 (2026-07-06 최초 분석)

- 프레임워크: 자체 PHP (`nfor` 프리픽스). 진입 설정 `config.php` → `$nfor[...]`. DB 접속 `nfor.php`.
- 핵심 테이블: `nfor_member`(회원=입점업체·광고주·리뷰어 공용), `nfor_campaign`(캠페인), `nfor_review`(리뷰), `nfor_campaign_order`(결제), `nfor_config`(사이트 설정값).
- **광고주/입점업체 개념**: `nfor_member.mb_id`가 `adv_` 접두 → 광고주, 아니면 입점업체(supply). 캠페인은 `nfor_campaign.cp_supply_no`(입점업체/광고주 회원번호) + `cp_md_no`/`cp_md_name`(담당 MD, 전산 직원명)로 연결. 캠페인 등록 폼(`admin/campaign_form.php`)에 입점업체/광고주 선택칸 존재(2026-06 신설).
- **캠페인 단위 성과·보고서 이미 구현**: `admin/campaign_report.php`(퍼널 rv_step·조회수 cp_click·referer 유입·IP 방문/전환·요일별 분석), `admin/campaign_report_share.php`(광고주 공유 보고서, 캠페인 1건 단위).
- 전산 연동 기존 API: `mc_cal_api.php`(캘린더·토큰), `mc_point_bank_api.php`(포인트출금), `sso.php`(HMAC 자동로그인). 전산측 클라이언트: metainc-web-backend `MetacrewCalendarClient`/`MetacrewPointBankClient`.

## 현재 진행 중 작업

- **광고주 공유 대시보드 — 메타체험단 연동** [상세: `docs/META_CHEHUMDAN_SETUP.md`]. 목표: 전산 광고주 ↔ 메타체험단 입점업체 매핑 → 광고주별 **전 캠페인 누적 집계 API** 신설 → 전산 공유 대시보드가 취합. 작업 브랜치 `claude/advertiser-shared-dashboard-05i742`.
  - **2026-07-23: `mc_advertiser_api.php` 구현 완료** — 토큰 인증 JSON·읽기전용 additive(SELECT만·기존 파일 무변경). 2모드: `?mode=advertisers`(광고주 목록=`mb_admin='1'`+`mb_id 'adv_'` 접두·미탈퇴) / `?supply_no=`(광고주별 누적: total·회차별·리뷰 링크). 신청/선정/리뷰는 **`nfor_review.rv_step` 재계산**(1신청·2선정·3리뷰제출·4등록확인, apply=전체·select=2+3+4·review=3+4, rv_delete='0') = `admin/campaign_report.php:472-480`와 동일 정의. 회차=캠페인 `cp_sdatetime` 오름차순. **토큰 = `mc_share.php`의 `mc_share_secret()`(서버 전용 `mc_share_key.php` 로더)** 재사용·소스 하드코딩 없음 → **전산 클라이언트는 `mc_share_key` 값으로 인증**(형제 API `mc_cal_api.php`는 리터럴 하드코딩이라 토큰이 다름 — 의도적, 공유키 방식이 자격증명 교체 런북 #4와 정합). `php -l` 통과. **FTP 배포 대기**(`mc_advertiser_api.php` 1개 업로드 — git≠라이브라 배포 전 라이브와 동기 확인). 동봉: 자격증명 교체 런북(계정 보유자용) 전달. **다음: 전산 `MetacrewAdvertiserClient` 배선 + 대시보드 체험단 카드 병합**(섹션① BE).
