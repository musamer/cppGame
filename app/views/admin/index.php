<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">لوحة تحكم المشرف</h1>

        <?php Session::flash('success'); ?>
        <?php Session::flash('error'); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Content Management Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg mr-4">
                        <i class="fas fa-layer-group text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">إدارة المحتوى</h2>
                        <p class="text-gray-500 text-sm">إدارة المراحل والعوالم والتمارين</p>
                    </div>
                </div>
                <div class="space-y-3 mt-6">
                    <a href="<?php echo URLROOT; ?>/admin/stages" class="block w-full text-center py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        إدارة المراحل
                    </a>
                    <a href="<?php echo URLROOT; ?>/admin/exercises" class="block w-full text-center py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        إدارة التمارين
                    </a>
                </div>
            </div>

            <!-- Danger Zone Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-red-100">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-red-100 text-red-600 rounded-lg mr-4">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">منطقة الخطر</h2>
                        <p class="text-gray-500 text-sm">إجراءات لا يمكن التراجع عنها</p>
                    </div>
                </div>

                <div class="mt-8 border-t pt-4">
                    <h3 class="font-medium text-red-600 mb-2">تصفير كافة الإجابات</h3>
                    <p class="text-gray-600 text-sm mb-4">سيتم حذف كافة حلول الطلاب والـ XP والمراحل المفتوحة، مع الإبقاء فقط على حسابات الطلاب المسجلة.</p>

                    <form action="<?php echo URLROOT; ?>/admin/reset_progress" method="POST" onsubmit="return confirm('هل أنت متأكد تماماً؟ هذا الإجراء سيحذف كافة تقدم الطلاب ولا يمكن استعادته!');">
                        <input type="hidden" name="confirm_reset" value="1">
                        <button type="submit" class="w-full py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-undo-alt mr-2"></i>
                            تصفير كافة البيانات الآن
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>