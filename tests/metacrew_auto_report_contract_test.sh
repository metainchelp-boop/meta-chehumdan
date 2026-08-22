#!/usr/bin/env bash
set -euo pipefail

root="$(cd "$(dirname "$0")/.." && pwd)"

need() {
  local file="$1"
  local pattern="$2"
  local message="$3"
  if ! grep -Eq "$pattern" "$root/$file"; then
    echo "FAIL: $message" >&2
    exit 1
  fi
}

test -f "$root/lib/mc_campaign_report.lib.php" || {
  echo "FAIL: 공용 보고서 생성 모듈이 없습니다" >&2
  exit 1
}
test -f "$root/mc_advertiser_api.php" || {
  echo "FAIL: 운영 중인 광고주 집계 API가 기본 브랜치에 없습니다" >&2
  exit 1
}

need "lib/mc_campaign_report.lib.php" 'function mc_campaign_report_generate\(' "공용 생성 인터페이스가 없습니다"
need "lib/mc_campaign_report.lib.php" 'function mc_campaign_report_generate_missing\(' "완료 회차 소급 생성 인터페이스가 없습니다"
need "lib/mc_campaign_report.lib.php" 'function mc_campaign_report_enqueue\(' "리뷰 처리와 보고서 생성을 분리하는 대기열이 없습니다"
need "lib/mc_campaign_report.lib.php" 'function mc_campaign_report_process_queue\(' "대기열 처리 인터페이스가 없습니다"
need "lib/mc_campaign_report.lib.php" 'function mc_campaign_report_existing_paths\(' "기존 구형 토큰 보고서 주소를 보존하지 않습니다"
need "lib/mc_campaign_report.lib.php" '\.processing' "생성 중 새 리뷰 갱신 요청을 보호하는 대기열 선점이 없습니다"
need "lib/mc_campaign_report.lib.php" 'function mc_campaign_report_recover_stale_claims\(' "중단된 보고서 생성 작업을 다시 대기열로 복구하지 않습니다"
need "lib/mc_campaign_report.lib.php" 'rename\(' "보고서 파일을 원자적으로 교체하지 않습니다"
need "lib/mc_campaign_report.lib.php" "rv_delete='0'" "삭제된 리뷰를 공개 보고서에서 제외하지 않습니다"
need "lib/mc_campaign_report.lib.php" 'JSON_HEX_TAG' "차트 데이터를 공개 HTML에 안전하게 직렬화하지 않습니다"
need "lib/mc_campaign_report.lib.php" 'mc_campaign_report_mask_name\(\$data\[mb_name\]\)' "차트에 리뷰어 실명을 그대로 공개합니다"
need "lib/mc_campaign_report.lib.php" 'FILTER_VALIDATE_URL' "공개 보고서 링크의 URL 형식을 검증하지 않습니다"
need "mc_advertiser_api.php" 'mc_adv_public_url' "공개 API 리뷰 링크의 URL 형식을 검증하지 않습니다"
need "mc_advertiser_api.php" 'mc_adv_public_url\(\$BASE\."/report/"' "공개 보고서 주소의 URL 형식을 검증하지 않습니다"
need "admin/campaign_report_share.php" 'mc_campaign_report_generate\(' "관리자 수동 생성이 공용 모듈을 사용하지 않습니다"
need "crontab/campaign.php" 'mc_campaign_report_generate_missing\(' "일일 크론이 완료 회차를 소급 생성하지 않습니다"
need "lib/campaign.lib.php" 'mc_campaign_report_enqueue\(' "리뷰 승인·취소가 보고서 갱신을 예약하지 않습니다"
if grep -Eq 'mc_campaign_report_refresh_after_review\(' "$root/lib/campaign.lib.php"; then
  echo "FAIL: 리뷰 승인·취소 요청 안에서 보고서를 동기 생성합니다" >&2
  exit 1
fi
need "mc_advertiser_api.php" '"report_public_url"' "공개 API가 보고서 주소를 전달하지 않습니다"
need "mc_advertiser_api.php" 'is_file\(' "공개 API가 파일 존재를 확인하지 않습니다"

echo "PASS: 메타체험단 자동 보고서 계약"
