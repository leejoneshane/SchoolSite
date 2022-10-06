<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite
</head>
<body>
    <div class="flex items-center justify-center h-screen p-5 bg-gray-100 w-screen">
        <div class="max-w-xl p-8 text-gray-800 bg-white shadow-xl lg:max-w-3xl rounded-3xl lg:p-12"">
            <div class="flex items-center justify-center">
                <img src="data:image/jpeg;base64,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" class="m-5">
            </div>
            <h3 class="text-2xl">敬愛的家長{{ $enroll->parent }}，您好：</h3>
            <div class="mt-4">
                首先感謝您為貴子弟（{{ $enroll->student->classroom->name }}{{ $enroll->student->seat }}號{{ $enroll->student->realname }}）報名參加{{ $enroll->club->name }}，
                您已經完成報名手續，但因成班之前仍有學生異動的可能，待錄取作業完成後，將另行公告通知！
                社團相關資訊如下：
                <table class="border w-full py-4 text-left font-normal">
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            負責單位
                        </th>
                        <td class="p-2">
                            {{ $enroll->club->unit->name }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            上課時間
                        </th>
                        <td class="p-2">
                            {{ $enroll->club->studytime }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            指導老師
                        </th>
                        <td class="p-2">
                            {{ $enroll->club->teacher }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            授課地點
                        </th>
                        <td class="p-2">
                            {{ $enroll->club->location }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            招生人數
                        </th>
                        <td class="p-2">
                            {{ $enroll->club->total }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            已報名
                        </th>
                        <td class="p-2">
                            截至 {{ now()->format('Y-m-d H:i:s') }} 為止，共{{ $enroll->club->count_enrolls() }}人
                        </td>
                    </tr>
                </table>
                報名時填寫內容如下：
                <table class="border w-full py-4 text-left font-normal">
                    @if ($enroll->club->has_lunch)
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            午餐選擇
                        </th>
                        <td class="p-2">
                            {{ ($enroll->need_lunch == 0) ? '自理' : '' }}{{ ($enroll->need_lunch == 1) ? '葷食' : '' }}{{ ($enroll->need_lunch == 2) ? '素食' : '' }}
                        </td>
                    </tr>
                    @endif
                    @if ($enroll->club->self_defined)
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            自選上課日
                        </th>
                        <td class="p-2">
                            每週{{ $enroll->weekday }}
                        </td>
                    </tr>
                    @endif
                    @if ($enroll->identity > 0)
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            身份註記
                        </th>
                        <td class="p-2">
                            {{ ($enroll->identity == 1) ? '低收入戶' : '' }}{{ ($enroll->identity == 2) ? '身心障礙' : '' }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            聯絡人
                        </th>
                        <td class="p-2">
                            {{ $enroll->parent }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            連絡信箱
                        </th>
                        <td class="p-2">
                            {{ $enroll->email }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            聯絡電話
                        </th>
                        <td class="p-2">
                            {{ $enroll->mobile }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="p-2 bg-green-100">
                            錄取順位
                        </th>
                        <td class="p-2">
                            {{ $order }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="text-gray-300 text-sm" role="alert">
                這是系統自動寄發的電子郵件，請勿直接回覆！若您並未報名，請忽略此信件！
            </div>
            <p class="mt-4 text-sm text-gray-500">© {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>