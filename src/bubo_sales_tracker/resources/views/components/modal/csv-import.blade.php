<div x-data="{ show: false }">
    <x-modal.common.show-modal-button buttonTitle="CSVインポート"></x-modal.common.show-modal-button>
    <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
        <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 600px;">
            <form method="POST" action="{{ route('csv.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <x-modal.common.header title="CSVインポート"></x-modal.common.header>
                    <div class="p-3 flex-grow">
                        <x-modal.common.file-upload-section desc="・ファイル選択ボタンをクリックして、CSVファイルを選択してください。"></x-modal.common.file-upload-section>
                        <x-modal.common.yes-no-radio-button desc="・インポート時に既存の登録データを消去しますか？"></x-modal.common.yes-no-radio-button>
                    </div>
                    <div class="px-3 py-3 border-t">
                        <div class="flex justify-end">
                            <x-modal.common.cancel-button buttonTitle="キャンセル"></x-modal.common.cancel-button>
                            <x-modal.common.submit-button buttonTitle="インポート"></x-modal.common.submit-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-black opacity-50"></div>
    </div>
</div>
