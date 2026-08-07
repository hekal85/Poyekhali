#Requires -Version 5.1
<#
    update-v3.ps1
    -------------
    بيركّب فوق موقع "بيخالي" التحديث الثالث:
    - 6 لغات جديدة (روسي، فرنسي، ألماني، إيطالي، أسباني، صيني) + قائمة لغات منسدلة
    - مرفقات متعددة لرسائل التواصل (جدول submission_attachments) + عرض الصور مباشرة + تحميل شغال 100%
    - سكيمة الدول/التأشيرات الجديدة اللي حددتها بالظبط (processing_time_ar بقى في visa_types + is_active لكل نوع تأشيرة)
    - سيدر تلقائي لـ 10 دول × 10 أنواع تأشيرة بمستنداتها (بيشتغل تلقائي مع migrate:fresh --seed)
    - صفحة "ابدأ طلبك" + نظام تتبع الطلبات (رقم طلب + رقم جواز سفر) + حالات مترجمة
    - تسجيل دخول/عضوية للعملاء
    - لوجو "Поехали!" + صورة صاروخ سويوز (رخصة عامة) + خلفية هيرو متغيرة
    - لوحة تحكم: إدارة الطلبات (applications) بالإضافة لكل حاجة سابقة

    الاستخدام (من جذر مشروع Laravel):
      powershell -ExecutionPolicy Bypass -File "المسار\update-v3.ps1" -ZipPath "المسار\poyekhali-visa-v3-kit.zip"
#>

param(
    [string]$ZipPath = ".\poyekhali-visa-v3-kit.zip",
    [string]$ProjectRoot = "."
)

$ErrorActionPreference = "Stop"

function Write-Step($msg) { Write-Host ""; Write-Host "==> $msg" -ForegroundColor Cyan }
function Write-Ok($msg) { Write-Host "    OK: $msg" -ForegroundColor Green }
function Write-Warn($msg) { Write-Host "    تنبيه: $msg" -ForegroundColor Yellow }

Write-Step "التأكد من مسار المشروع"
if (-not (Test-Path (Join-Path $ProjectRoot "artisan"))) {
    Write-Host "خطأ: مش لاقي ملف artisan في '$ProjectRoot'. شغّل السكريبت من جذر مشروع Laravel." -ForegroundColor Red
    exit 1
}
Write-Ok "المشروع موجود في: $((Resolve-Path $ProjectRoot).Path)"

Write-Step "التأكد من ملف الحزمة (kit)"
if (-not (Test-Path $ZipPath)) {
    Write-Host "خطأ: مش لاقي poyekhali-visa-v3-kit.zip في '$ZipPath'." -ForegroundColor Red
    exit 1
}
Write-Ok "لقيت الحزمة: $ZipPath"

Write-Step "فك ضغط الملفات"
$tempExtract = Join-Path $env:TEMP ("poyekhali-v3-" + [guid]::NewGuid())
Expand-Archive -Path $ZipPath -DestinationPath $tempExtract -Force
Write-Ok "اتفكت في: $tempExtract"

Write-Step "نسخ الملفات داخل المشروع"
Get-ChildItem -Path $tempExtract -Recurse -File | ForEach-Object {
    $relativePath = $_.FullName.Substring($tempExtract.Length).TrimStart('\', '/')
    $destPath = Join-Path $ProjectRoot $relativePath
    $destDir = Split-Path $destPath -Parent
    if (-not (Test-Path $destDir)) { New-Item -ItemType Directory -Path $destDir -Force | Out-Null }
    Copy-Item -Path $_.FullName -Destination $destPath -Force
    Write-Host "    نسخ: $relativePath"
}
Write-Ok "اتنسخت كل الملفات"

Push-Location $ProjectRoot
try {
    Write-Step "تشغيل الميجريشنز الجديدة (إضافية بس - من غير ما تمسح بياناتك الحالية)"
    php artisan migrate --force
    Write-Ok "الجداول اتحدّثت"

    Write-Step "تحديث/زرع كتالوج الدول والتأشيرات (10 دول × 10 أنواع تأشيرة)"
    php artisan db:seed --class=Database\Seeders\VisaCatalogSeeder --force
    Write-Ok "تم"

    Write-Step "التأكد من وجود حساب أدمن"
    php artisan db:seed --class=Database\Seeders\AdminUserSeeder --force
    Write-Ok "تم"

    Write-Step "ربط storage (لو مش مربوط قبل كده)"
    php artisan storage:link
    Write-Ok "تم"

    Write-Step "مسح الكاش"
    php artisan optimize:clear
    Write-Ok "تم"
} catch {
    Write-Warn "حصل خطأ أثناء تشغيل أوامر artisan: $($_.Exception.Message)"
    Write-Warn "شغّل الأوامر دي يدويًا لو لزم الأمر:"
    Write-Host "    php artisan migrate --force"
    Write-Host "    php artisan db:seed --class=Database\Seeders\VisaCatalogSeeder --force"
    Write-Host "    php artisan db:seed --class=Database\Seeders\AdminUserSeeder --force"
    Write-Host "    php artisan storage:link"
}
Pop-Location

Remove-Item -Path $tempExtract -Recurse -Force -ErrorAction SilentlyContinue

Write-Host ""
Write-Host "=========================================" -ForegroundColor Green
Write-Host " ملحوظة مهمة عن migrate:fresh --seed" -ForegroundColor Green
Write-Host "=========================================" -ForegroundColor Green
Write-Host "السكريبت ده شغّل migrate عادي (إضافي وآمن) مش migrate:fresh، عشان متفقدش"
Write-Host "رسائل الزوار والطلبات اللي عندك بالفعل في قاعدة البيانات."
Write-Host ""
Write-Host "لو حبيت مسح كل حاجة والبدء من الصفر بالبيانات الافتراضية بس (زي بيئة تجربة جديدة)،"
Write-Host "دلوقتي تقدر تشغّل يدويًا وقت ما تحب:"
Write-Host "    php artisan migrate:fresh --seed"
Write-Host "وهيزرع تلقائي كل الدول العشرة وأنواع التأشيرات وحساب الأدمن (زي ما طلبت بالظبط)."
Write-Host ""
Write-Host "=========================================" -ForegroundColor Green
Write-Host " خطوات يدوية متبقية" -ForegroundColor Green
Write-Host "=========================================" -ForegroundColor Green
Write-Host "1. في .env ضيف/اتأكد من:"
Write-Host "   ADMIN_NOTIFICATION_EMAIL=hekal_85@hotmail.com"
Write-Host "   SITE_CONTACT_PHONE=+79936445881"
Write-Host "   SITE_CONTACT_WHATSAPP=79936445881"
Write-Host "   SITE_CONTACT_EMAIL=hekal_85@hotmail.com"
Write-Host "   SITE_CONTACT_TELEGRAM=hekal_85"
Write-Host "   (اختياري - لتفعيل إشعار تيليجرام تلقائي للإدارة عند أي طلب جديد)"
Write-Host "   TELEGRAM_BOT_TOKEN=..."
Write-Host "   TELEGRAM_CHAT_ID=..."
Write-Host "   (اختياري - لتفعيل إرسال واتساب تلقائي للعميل، محتاج حساب WhatsApp Business API"
Write-Host "    زي Twilio أو UltraMsg أو Wassenger)"
Write-Host "   WHATSAPP_API_URL=..."
Write-Host "   WHATSAPP_API_TOKEN=..."
Write-Host ""
Write-Host "2. لو أول مرة تركّب middleware الأدمن (من التحديث اللي فات)، تأكد إن bootstrap/app.php فيه:"
Write-Host '   $middleware->alias([''admin'' => App\Http\Middleware\EnsureUserIsAdmin::class]);'
Write-Host ""
Write-Host "3. شغّل: npm run dev"
Write-Host ""
