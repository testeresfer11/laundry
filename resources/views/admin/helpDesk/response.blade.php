@extends('admin.layouts.app')
@section('title', 'Query Response')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Help Desk</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.helpDesk.list',['type' => 'open'])}}">Help Desk</a></li>
        <li class="breadcrumb-item active" aria-current="page">Response</li>
      </ol>
    </nav>
</div>
@endsection
@section('content')

<div class="help-response">
  <h4 class="response-title d-flex justify-content-between align-items-center">
    <div>
      <h6 class="f-14 mb-1"><span class="semi-bold qury">Title :</span> <span class="text-muted"> {{$response->title}} </span></h6>
      <h6 class="f-14 mb-1"><span class="semi-bold qury ">Description :</span> <span class="text-muted">{{$response->description}}</span></h6>
    </div>
    <div>
      <h6 class="f-14 mb-1"><span class="semi-bold qury help-id"># {{$response->ticket_id}} </span></h6>
    </div>
  </h4>
  <div class="row justify-content-center">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
           
            
          <div class="card-header d-flex justify-content-between p-3">
            <p class="fw-bold mb-0">Admin</p>
           
          </div>
          <ul class="list-unstyled chat-box">
            @forelse ($response->response as $data)
              @if (!empty($data->response) || !empty($data->response_image))
                @if ($data->user_id == authId())
                  {{-- Right Side: Current User --}}
                  @if (!empty($data->response))
                    <li class="d-flex mb-4 right-msg">
                      <div class="card w-100">
                        <div class="card-body">
                          <p class="mb-0">{{$data->response}}</p>
                          <p class="text-muted small mb-0 msg_time">{{ replyDiffernceCalculate($data->created_at) }} ago</p>
                        </div>
                      </div>
                      <img src="{{ userImageById($data->user_id) }}" alt="avatar"
                        class="rounded-circle d-flex align-self-start ms-3 shadow-1-strong" width="60">
                    </li>
                  @endif
          
                  @if (!empty($data->response_image))
                    <li class="d-flex mb-4 right-msg justify-content-end">
                      <div class="mt-2 text-end">
                        @php
                          $filePath = 'storage/images/' . $data->response_image;
                        @endphp
                        <img src="{{ asset($filePath) }}" alt="attached image"
                          class="img-fluid rounded shadow-sm" style="width:250px;height:250px;max-width: 300px;">
                      </div>
                      <img src="{{ userImageById($data->user_id) }}" alt="avatar"
                        class="rounded-circle d-flex align-self-start ms-3 shadow-1-strong" width="60">
                    </li>
                  @endif
                @else
                  {{-- Left Side: Other User --}}
                  <li class="d-flex mb-4 left-msg">
                    <img src="{{ userImageById($data->user_id) }}" alt="avatar"
                      class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="60">
                    <div class="card">
                      <div class="card-body">
                        @if (!empty($data->response))
                          <p class="mb-0">{{ $data->response }}</p>
                        @endif
                        @if (!empty($data->response_image))
                          @php
                            $filePath = 'storage/images/' . $data->response_image;
                          @endphp
                          <img src="{{ asset($filePath) }}" alt="attached image"
                            class="img-fluid rounded shadow-sm mt-2" style="width:250px;height:250px;max-width: 300px;">
                        @endif
                        <p class="text-muted small mb-0 msg_time">{{ replyDiffernceCalculate($data->created_at) }} ago</p>
                      </div>
                    </div>
                  </li>
                @endif
              @endif
            @empty
              <div class="col-12 text-center">
                <img class="mt-4" src="{{ asset('admin/images/faces/no-record.png') }}" height="300px">
              </div>
            @endforelse
          </ul>
          
        </div>
        <form id="query-response" action="{{route('admin.helpDesk.response',['id' => $response->id])}}" method="POST" enctype="multipart/form-data">
              @csrf
              @can('helpdesk-reply')
                <div class="card messages-card mb-1">
                  <div data-mdb-input-init class="form-outline">
                    <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="textAreaExample2" rows="4" placeholder="Type..." name="response"></textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="attachment_area"> 
                    <p id="fileName"></p>
                    <label for="response_image">
                    <i class="fa fa-paperclip file-browser"></i> 
                    </label>
                    <input type="file" onchange="displayFileName()" id="response_image" name="response_image" class="form-control visually-hidden" accept="image/*, video/*">
                    <button type="submit"><i class="fa fa-paper-plane"></i></button>
                  </div>
                  </div>
                </div>
              @endcan
          
              
        </form>
      </div>

    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    $("#query-response").submit(function(e){
        e.preventDefault();
    }).validate({
      rules: {
        response: {
          required: function(element) {
            return $('#response_image').get(0).files.length === 0;
          },
        },
        response_image: {
          extension: "jpg|jpeg|png|gif",
        }
      },
      messages: {
        response: {
          required: "Please enter a response or attach an image."
        },
        response_image: {
          extension: "Only image files are allowed (jpg, jpeg, png, gif)."
        }
      },
        submitHandler: function(form) {
          form.submit();
      }

    });
  });
  </script>
  <script>
    function displayFileName() {
      const fileInput = document.getElementById('response_image');
      const fileName = document.getElementById('fileName');
      
      // Check if any file is selected
      if (fileInput.files.length > 0) {
        fileName.textContent = `${fileInput.files[0].name}`;
      } else {
        fileName.textContent = 'No file selected';
      }
    }
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.querySelector('form');
      const button = form.querySelector('button[type="submit"]');
      const textInput = form.querySelector('textarea[name="response"]');
      const fileInput = form.querySelector('input[type="file"][name="response_image"]');
  
      form.addEventListener('submit', function (e) {
        e.preventDefault();
  
        const hasText = textInput.value.trim() !== '';
        const hasFile = fileInput.files.length > 0;
  
        if (!hasText && !hasFile) {
          return; // Do nothing if both fields are empty
        }
  
        // Disable button and show spinner
        button.disabled = true;
        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
  
        // Submit the form normally
        form.submit();
  
        // Or optionally reload (useful for non-AJAX)
        setTimeout(() => {
          location.reload();
        }, 2000); 
      });
    });
  </script>
@stop