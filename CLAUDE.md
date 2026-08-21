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
  - **⚠️ 정정(2026-07-24): 누적집계 API는 이미 2026-07-07에 배포돼 있었음 — 재작업 불필요** — `mc_advertiser_api.php`(**10,935B**, 라이브 서버 `/www`에 07-07 15:29 존재)가 **완전한 정본**: 토큰 인증(서버 전용 `mc_api_token.php` 로더·`.gitignore` 등재)·읽기전용·2모드(`mode=advertisers`/`supply_no`)·total(방문 트래픽 `nfor_traffic_YYYY_WW`·visit/visit_uniq 포함)·회차별·**매체 분류**(referer 기반 네이버블로그/카페/검색/인스타/유튜브 등)·리뷰 링크. **전산 `MetacrewAdvertiserClient` 계약과 정확히 일치**(media[]·visit·visit_uniq 소비). ⚠️ 2026-07-24 세션이 이 정본을 '스텁'으로 오인해 축소본(8,650B·트래픽/매체 제거·토큰을 mc_share_secret로 교체)으로 재작성·커밋했다가 **되돌림**(`git checkout 27ab8d8 -- mc_advertiser_api.php .gitignore`로 복원, mc_api_token.php gitignore 라인도 복원). **FTP 재배포 불필요**(서버가 이미 정본). ⭐ **교훈: 라이브(FTP) 서버 파일은 git 사본보다 최신·완전할 수 있다 — 덮어쓰기 전 반드시 서버 버전과 크기·내용 대조**(FileZilla 덮어쓰기 경고가 실사고 방지). **남은 것(연동 켜기)**: 전산 ENV(`METACREW_ADVERTISER_URL`·`METACREW_ADVERTISER_TOKEN`=서버 `mc_api_token.php` 반환 토큰값[※mc_share_key 아님]·`METACREW_ADVERTISER_ENABLED=true`) + 재배포 + 공유링크 관리에서 광고주↔supply_no 1클릭 매핑. (전산 `MetacrewAdvertiserClient`·`ClientPortalService.buildMetacrew`·`ShareLink.metacrewSupplyNo`·매핑 드롭다운은 이미 배포됨.)
  - **✅ 연동 켜짐·실측 검증 완료 (2026-07-24)** — 전산 ENV 3종 주입 후 재배포(deploy-vps **#112 success**·blue-green 무중단·green 헬스체크 통과) → 서버·컨테이너에서 실측 확인: `METACREW_ADVERTISER_URL/ENABLED=true/TOKEN(길이 48)` 컨테이너 반영 · 서버 호스트→API **HTTP 200** · 컨테이너(자바 앱 경로)→API **HTTP 200** · 응답 `{"ok":true,"count":161}`(광고주 161개) · BE 로그 `MetacrewAdvertiser` 실패 0건. 전산 공유링크 관리 「메타체험단 연동」 검색도 목록 정상 렌더(대표 확인). ⚠️ **토큰 주입 방식 = ENV_FILE 미변경**: 별도 시크릿 `METACREW_ADVERTISER_TOKEN` 신설 + BE `deploy-vps.yml` 이 `.env` 말미에 3줄 append(시크릿 미설정 시 토큰 빈값→`isEnabled()=false`로 연동만 자동 OFF·무해). ENV_FILE 전체 덮어쓰기 사고(7/14 GEMINI 선례) 회피용 — **앞으로도 ENV_FILE 직접 편집 금지, 새 값은 별도 시크릿+deploy-vps append 패턴 사용**. ⚠️ 검색 0건 = 코드/연동 문제 아님, **그 업체가 체험단에 광고주(adv_ 계정)로 미등록**이라는 뜻(예: 평창밤나무골농원 — 대표 확인). 체험단에 광고주 등록 후 검색·연결하면 카드 자동 표시.
  - **✅ 회차별 결과 보고서 연동 — FTP 반영 완료 (2026-08-21 18:30)** — `mc_advertiser_api.php` **10,935B → 13,321B**(대표가 FileZilla 로 `/www` 덮어쓰기, 서버 목록 실측 확인). 캠페인마다 공개 보고서 파일이 **실제로 있는지 확인해** `report_public_url`·`report_at` 두 칸을 가산한다. 기존 `report_url`(관리자 로그인 필요 화면)은 그대로 뒀다 — 다른 소비자가 있을 수 있어 덮어쓰지 않았다. ⚠️ **옛 보고서는 토큰이 짧다**(2026-06-18 이전 10자 → 이후 24자). 정확 매칭만 하면 기존 10건 중 9건이 「보고서 없음」이 되므로 캠페인 번호 glob 폴백을 뒀다. **파일이 있을 때만 주소를 준다** — 주소만 주고 파일이 없으면 광고주가 눌렀을 때 「찾을 수 없음」이 뜬다. 전산(BE `MetacrewAdvertiserClient.Campaign` · FE `ClientSharePage` 「📄 회차별 결과 보고서」)은 8/21 배포 완료. **전산 캐시 5분**(`metacrew.advertiser.cache-seconds:300`) — 올린 직후 안 보이면 5분 뒤 새로고침.
  - **⚠️ 자동 FTP 업로드는 아직 못 쓴다 (2026-08-21)** — `.github/workflows/ftp-upload.yml` 신설(수동 실행 · 지정 파일만 · 기본 미리보기 · 서버 크기 대조 후 중단). 시크릿 4종 등록·로그인 성공(`230 User logged in`)까지 되는데 **그 계정이 어떤 폴더에도 못 들어간다** — 서버가 `550 PWD: Permission denied` · `450 /www: No such file or directory`, `/`·`/www`·절대경로·계정폴더 전부 `CWD` 거부. **경로 값 문제가 아니다**(9회 실측). 해결책은 카페24에서 **접속 폴더를 `/www` 로 지정한 FTP 계정을 새로 만들어** 시크릿 교체. 부수 확인 2건: ① 이 서버는 **FTPS 미지원**(`AUTH TLS → 500 not understood`) — 자동화 시 비밀번호 평문 전송, 전용 계정 권장. ② **카페24는 해외 IP 에 자바스크립트 검증 페이지(cupid.js)를 HTTP 200 으로 물린다** — 깃허브(미국) 러너에서 응답 코드만 보고 「정상」이라 판정하면 거짓이다(워크플로우에 검출 로직 추가). 국내에서 봐야 확인된다.
