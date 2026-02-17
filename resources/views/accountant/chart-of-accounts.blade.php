@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Chart of Accounts</h1>

    <div class="space-y-6">
        @foreach($accounts as $category => $accountList)
        <div class="bg-white rounded-lg shadow">
            <div class="bg-gray-50 px-6 py-3 border-b">
                <h3 class="text-lg font-semibold">{{ $category }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($accountList as $account)
                    <div class="border rounded p-3 hover:bg-gray-50">
                        <div class="font-medium">{{ $account }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection