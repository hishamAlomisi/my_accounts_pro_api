<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin requests. You may
    | enable or disable specific features, or adjust the settings as needed.
    |
    */

    'paths' => ['api/*','user/*','web/*'],  // المسارات التي يتم تفعيل CORS لها

    'allowed_methods' => ['*'],  // طرق HTTP المسموح بها (GET, POST, PUT, DELETE، الخ)

    'allowed_origins' => ['*'],  // النطاقات المسموح بها (يمكن تحديد أكثر من نطاق أو استخدام * لكل النطاقات)

  'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],//الرؤوس المسموح بها في الطلبات

    'exposed_headers' => [],  // الرؤوس التي يمكن أن تعرضها الخوادم للعملاء

    'max_age' => 0,  // المدة التي يمكن فيها تخزين النتائج في الذاكرة (من حيث عدد الثواني)

    'supports_credentials' => false,  // إذا كان يجب السماح باستخدام الكوكيز أو بيانات المصادقة الأخرى عبر CORS


];
