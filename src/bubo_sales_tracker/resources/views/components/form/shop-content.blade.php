<x-form.common.title :formTitle="$formTitle"></x-form.common.title>
<x-form.common.error-message></x-form.common.error-message>
<div class="px-4 py-5 bg-white space-y-6 sm:p-6">
    <x-form.common.input-section
        labelTitle="店舗名"
        inputName="shop_name"
        type="text"
        :currentValue="$shopName">
    </x-form.common.input-section>
    <x-form.common.input-section
        labelTitle="スマレジ店舗ID"
        inputName="sumaregi_tenpo_id"
        type="number"
        :currentValue="$sumaregiTenpoId">
    </x-form.common.input-section>
</div>
<div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
    <x-form.common.submit-button :submitTitle="$submitTitle"></x-form.common.submit-button>
</div>
