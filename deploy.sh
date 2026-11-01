#!/usr/bin/env bash
# Linkora Quick Deployment Wrapper
set -e
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
bash "$SCRIPT_DIR/server_config/deploy.sh"
