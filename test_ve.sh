#!/bin/bash
curl -s -c /tmp/cj_ve.txt -b /tmp/cj_ve.txt -L https://yourlead.ru/admin/login > /dev/null 2>&1
# Extract CSRF and login
CSRF=$(curl -s -c /tmp/cj_ve.txt -b /tmp/cj_ve.txt https://yourlead.ru/admin/login 2>&1 | grep -oP 'csrf-token" content="\K[^"]+')
curl -s -c /tmp/cj_ve.txt -b /tmp/cj_ve.txt \
  --data "_token=$CSRF&login=admin&password=admin123&remember=true" \
  https://yourlead.ru/admin/login > /dev/null 2>&1
# Now access visual editor
curl -s -b /tmp/cj_ve.txt https://yourlead.ru/admin/pages/1/visual-editor 2>&1 > /tmp/ve_output.html
echo "Page size: $(wc -c < /tmp/ve_output.html)"
echo "HTTP status: $(curl -s -o /dev/null -w '%{http_code}' -b /tmp/cj_ve.txt https://yourlead.ru/admin/pages/1/visual-editor 2>&1)"
echo "Contains widgetFields: $(grep -c 'widgetFields' /tmp/ve_output.html)"
echo "Contains data-settings-json: $(grep -c 'data-settings-json' /tmp/ve_output.html)"
echo "Contains ve-modal: $(grep -c 've-modal' /tmp/ve_output.html)"
echo "=== widgetFields preview ==="
grep -oP 'widgetFields = \{[^;]+' /tmp/ve_output.html | head -c 2000
echo ""
echo "=== data-settings-json first match ==="
grep -oP 'data-settings-json="[^"]*"' /tmp/ve_output.html | head -3
echo ""
echo "=== JS errors check ==="
grep -oP 'Uncaught|Error:|error|throw' /tmp/ve_output.html | head -5
