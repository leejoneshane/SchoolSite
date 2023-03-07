@extends('layouts.main')

@section('content')
<div class="text-2xl font-bold leading-normal pb-5">
    分組座位表
    <a class="text-sm py-2 pl-6 rounded text-blue-300 hover:text-blue-600" href="{{ route('seats.add') }}">
        <i class="fa-solid fa-circle-plus"></i>新增座位表
    </a>
    <a class="text-sm py-2 pl-6 rounded text-blue-300 hover:text-blue-600" href="{{ route('seats.theme') }}">
        <i class="fa-solid fa-chess-board"></i>管理版型
    </a>
</div>
<table class="w-full py-4 text-left font-normal">
    <tr class="bg-gray-300 dark:bg-gray-500 font-semibold text-lg">
        <th scope="col" class="p-2">
            班級
        </th>
        <th scope="col" class="p-2">
            版型
        </th>
        <th scope="col" class="p-2">
            建立日期
        </th>
        <th scope="col" class="p-2">
            管理
        </th>
    </tr>
    @forelse ($seats as $s)
    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-700 dark:even:bg-gray-600 hover:bg-green-100">
        <td class="p-2 cursor-pointer" onclick="
            window.location.replace('{{ route('seats.view', ['id' => $s->id]) }}');
        ">{{ $s->classroom->name }}</td>
        <td class="p-2 cursor-pointer" onclick="
            window.location.replace('{{ route('seats.view', ['id' => $s->id]) }}');
        ">{{ $s->theme->name }}</td>
        <td class="p-2 cursor-pointer" onclick="
            window.location.replace('{{ route('seats.view', ['id' => $s->id]) }}');
        ">{{ $s->created_at }}</td>
        <td class="p-2">
            <button class="py-2 pr-6 text-red-300 hover:text-red-600"
                onclick="
                    const myform = document.getElementById('remove');
                    myform.action = '{{ route('seats.auto', ['id' => $s->id]) }}';
                    myform.submit();
            ">
                <i class="fa-solid fa-wand-magic-sparkles"></i>自動分組
            </button>
            <a class="py-2 pr-6 text-blue-300 hover:text-blue-600"
                href="{{ route('seats.edit', ['id' => $s->id]) }}">
                <i class="fa-solid fa-pen"></i>編輯座位
            </a>
            <a class="py-2 pr-6 text-blue-300 hover:text-blue-600"
                href="{{ route('seats.change', ['id' => $s->id]) }}">
                <i class="fa-solid fa-chess-board"></i>變更版型
            </a>
            <button class="py-2 pr-6 text-red-300 hover:text-red-600"
                onclick="
                    const myform = document.getElementById('remove');
                    myform.action = '{{ route('seats.remove', ['id' => $s->id]) }}';
                    myform.submit();
            ">
                <i class="fa-solid fa-trash"></i>刪除
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="text-xl font-bold">您尚未新增任何座位表！</td>
    </tr>
    @endforelse
    <form class="hidden" id="remove" action="" method="POST">
        @csrf
    </form>
</table>
@endsection
