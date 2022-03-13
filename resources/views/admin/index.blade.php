@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-2">
            <select class="form-select" aria-label="Default select example">
                <option selected disabled></option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" aria-label="Default select example">
                <option selected disabled></option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-primary ">Generate Report</button>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-bordered table-sm fs-6 fw-light">
                <caption>List of jobs</caption>
                <thead>
                  <tr>
                    <th scope="col">Job Title</th>
                    <th scope="col">Date Submitted</th>
                    <th scope="col">Is Indexed</th>
                  </tr>
                </thead>
                <tbody>
                    @forelse ($jobs as $job)
                        <tr>
                            <th><a href="{{ $job->url }}" class="fs-6 fw-light text-dark text-decoration-none" target="_blank">{{ $job->title }}</a></th>
                            <td>{{ Carbon\Carbon::parse($job->date)->diffForHumans() }}</td>
                            <td>
                                -
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>

            <div>
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
