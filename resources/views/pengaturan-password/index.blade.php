 @extends('layouts.main')

 @section('content')
     <div class="row">
         <div class="col-5">
             <div class="card card-primary">
                 @if (session()->has('success'))
                     <div class="alert alert-success alert-dismissible">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                         {{ session('success') }}
                     </div>
                 @endif

                 @if (session()->has('failed'))
                     <div class="alert alert-danger alert-dismissible">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                         {{ session('failed') }}
                     </div>
                 @endif

                 @error('password')
                     <div class="alert alert-danger alert-dismissible">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                         {{ $message }}
                     </div>
                 @enderror

                 @error('new_password')
                     <div class="alert alert-danger alert-dismissible">
                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                         {{ $message }}
                     </div>
                 @enderror

                 <div class="card-header">
                     <h3 class="card-title">Ganti Password</h3>
                 </div>

                 <form action="/edit-password" method="post">
                     @csrf
                     <div class="card-body">

                         <div class="form-group">
                             <label for="password">Password Lama</label>
                             <input type="text" class="form-control" id="password" name="password"
                                 placeholder="Password">
                         </div>

                         <div class="form-group">
                             <label for="new_password">Password Baru</label>
                             <input type="text" class="form-control" id="new_password" name="new_password"
                                 placeholder="Password">
                         </div>

                     </div>


                     <div class="card-footer">
                         <button type="submit" class="btn btn-primary">Submit</button>
                     </div>
                 </form>

             </div>
         </div>

     </div>
 @endsection
