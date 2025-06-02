 @extends('layouts.main')

 @section('content')
     <div class="row">
         <div class="col-lg-6">
             <div class="card">
                 <div class="card-body">
                     <h5 class="mb-2 card-title">Perubahan Aplikasi</h5>

                     <div class="card-body table-responsive p-0" style="height: 300px;">
                           <?php
                        $file_handle = fopen('pesan_commits', 'r');
                        $nomor = 0;
                        
                        while (!feof($file_handle)) {
                            $line = fgets($file_handle);
                        
                            if (strpos($line, 'restu')) {
                                $nomor++;
                            }
                        
                            echo str_replace('restu:', '<strong>Restu Winaldi:</strong> [' . $nomor . ']', $line) . '<br>';
                    
                        }
                        fclose($file_handle);
                        ?>
                     </div>

                 </div>
             </div>
         </div>
         <div class="col-lg-6">
             <div class="card">
                 <div class="card-header">
                     <h5 class="m-0">Featured</h5>
                 </div>
                 <div class="card-body">
                     <h6 class="card-title">{{ env('APP_NAME') }}</h6>

                     <p class="card-text">Sistem Managemen Rumahtangga meliputi stok data, dan pengembagan modul lainnya.</p>
                     
                     <h6 class="card-title"><b>Versi Aplikasi {{ env('APP_VERSION') }}</b></h6><br><br>

                     <a href="https://wa.me/6289669460382" class="btn btn-success"><i class="fa-brands fa-whatsapp"></i> Developer</a>

                    

                 </div>
             </div>
         </div>
     </div>
 @endsection
