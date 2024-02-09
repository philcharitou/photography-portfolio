@php use Carbon\Carbon; @endphp

@extends('system.layouts.sidebar')
@section('title', 'Bug Reports')

@section('content')

    <div class="p-8">
        <div class="m-5 border-light text-sm">
            <div class="p-3 bg-neutral-200 flex justify-between items-center">
                <span class="text-xl uppercase">Bug Reports</span>
                <form method="POST" action="{{ route('clear_bug_reports') }}">
                    @csrf
                    <button class="rounded-md bg-neutral-100 p-3">Clear Bug Reports</button>
                </form>
            </div>

            <div class="bg-white p-4">
                @if($reports->count())
                <table class="table-fixed table-striped w-full mb-6">
                    <thead>
                    <tr>
                        <th class="text-left" scope="col"><input type="checkbox" id="checkAll" onclick="SelectAll('')"></th>
                        <th class="text-left" scope="col">{{ __('pages.submitted_by')}}</th>
                        <th class="text-left" scope="col">{{ __('pages.subject')}}</th>
                        <th class="text-left" scope="col">{{ __('pages.details')}}</th>
                        <th class="text-left" scope="col">{{ __('pages.submitted')}}</th>
                        <th class="text-left" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reports->reverse() as $report)
                        <tr id="bug-reports[{{ $report->id }}]">
                            <td class="text-right">
                                <input type="checkbox" class="checkboxAll_" id="checkbox{{ $report->id }}"
                                       onclick="MultiSelect(this.id)">
                            </td>
                            <td class="border-end">{{ $report->author }}</td>
                            <td class="border-end">{{ $report->title }}</td>
                            <td class="border-end">{{ $report->body }}</td>
                            <td class="border-end text-no-wrap px-2">{{ Carbon::parse($report->created_at)->longRelativeToNowDiffForHumans() }}</td>

                            <!-- Menu -->
                            <td class="px-3">
                                <form method="POST" action="{{ route('bug-reports.destroy', [$report->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-square btn-delete-confirm ms-auto">
                                        <i class="fa-solid fa-x"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                    <div class="my-12 mx-auto">
                        <div class="text-xl mx-auto uppercase text-center">System is Officially Bug Free <span class="text-sm lowercase ms-2">(for now)</span></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- White Space -->
    <div class="row mx-0" style="height: 200px">

@endsection
