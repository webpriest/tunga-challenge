@if(session()->has('error'))
  <div class="bg-red-600 w-full rounded p-3 text-white mb-4">
    {!! session()->get('error') !!}
  </div>
@endif

@if(session()->has('success'))
  <div class="bg-green-600 w-full rounded p-3 text-white mb-4">
    {!! session()->get('success') !!}
  </div>
@endif