<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>    
    <meta name="csrf-token" content="{{ csrf_token() }}">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<style>
    .error    {color: red;}
    .ast-symbol {color:red;}
</style>

</head>
<body>
 
<div class="container mt-2">
 
<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>User Details</h2>
            </div>
            <div class="pull-right mb-2">
                <a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Create User</a>
            </div>
        </div>
    </div>

    <div class="display_message">
            
    </div>
     
    <div class="card-body">
 
        <table class="table table-bordered" id="ajax-crud-datatable">
           <thead>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Hobbies</th>
                <th>Action</th>
              </tr>
           </thead>
        </table>
 
    </div>
    
</div>
 
  <!-- boostrap user model -->
    <div class="modal fade" id="user-modal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="UserModal"></h4>
          </div>


          <div class="modal-body">
          <div id="validation-errors"> </div>
            <form action="javascript:void(0)" id="UserForm" name="UserForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="user_id" id="user_id" value=''>
             
              <div class="form-group">
                <label for="first_name" class="col-sm-2 control-label">First Name</label> <span class="ast-symbol">*</span>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" maxlength="50" >
                </div>
              </div>  
 
              <div class="form-group">
                <label for="last_name" class="col-sm-2 control-label">Last Name</label><span class="ast-symbol">*</span>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" maxlength="50">
                </div>
              </div>
 
              <div class="form-group">
               <label for="hobbies" class="col-sm-2 control-label">Hobbies</label><span class="ast-symbol">*</span>
                <div class="col-sm-12">
                  @php $hobbies = getAllHobbies(); @endphp
                  @if(!empty($hobbies))
                      @foreach($hobbies as $vals)
                        <input type="checkbox" id="hobbie_{{$vals->id}}" name="hobbies[]" value="{{$vals->id}}">
                        <label for="{{$vals->id}}"> {{$vals->hobbie_name}}</label><br>
                      @endforeach
                  @endif
                </div>
                </div>
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" id="btn-save">Save changes
                </button>
              </div>
            </form>
          </div>
          <div class="modal-footer">
             
          </div>
        </div>
      </div>
    </div>
<!-- end bootstrap model -->
 
</body>
<script type="text/javascript">
      
 $(document).ready( function () {
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  
    $('#ajax-crud-datatable').DataTable({
           processing: true,
           serverSide: true,
           ajax: "{{ url('user-list') }}",
           columns: [
                    {data: 'user_id', name: 'user_id'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'hobbies_name', name: 'hobbies_name'},
                    {data: 'action', name: 'action', orderable: false},
                 ],
                 order: [[0, 'desc']]
       });
 
  });
   
  function add(){
 
       $('#UserForm').trigger("reset");
       $('#UserModal').html("Add User");
       $('#user-modal').modal('show');
       $('#id').val('');
       $('#validation-errors').html('');
  }   
  

  function editFunc(user_id){
    $.ajax({
        type:"POST",
        url: "{{ url('edit-user') }}",
        data: { user_id: user_id },
        dataType: 'json',
        success: function(res){
          $('#UserModal').html("Edit User");
          $('#user-modal').modal('show');
          $('#validation-errors').html('');
          $('#user_id').val(res.user_id);
          $('#first_name').val(res.first_name);
          $('#last_name').val(res.last_name);
          var hid = res.hobbies_id.split(",");
          $.each(hid,function(i,val){
            $("#hobbie_"+val).prop('checked', true);
          });
       }
    });
  }  
 
  function deleteFunc(id){
        if (confirm("Delete Record?") == true) {
        var user_id = id;
          
          // ajax
          $.ajax({
              type:"POST",
              url: "{{ url('delete-user') }}",
              data: { user_id: user_id },
              dataType: 'json',
              success: function(res){
 
                $('.display_message').html('Record Deleted Successfully!!!').css("color", "green").show().fadeOut(5000); 
                var oTable = $('#ajax-crud-datatable').dataTable();
                oTable.fnDraw(false);
             }
          });
       }
  }

  $('form[id="UserForm"]').validate({  
    rules: {  
      first_name: 'required',  
      last_name: 'required',
      "hobbies[]":
            { 
                    required: true, 
                    minlength: 1 
            } 
  
    },  
    messages: {   
      
    },  
    submitHandler: function(form) {  

            var formData = new FormData(form);

              $.ajax({
                type:'POST',
                url: "{{ url('store-user')}}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                    success: (data) => {
                      $("#user-modal").modal('hide');
                      var oTable = $('#ajax-crud-datatable').dataTable();
                      oTable.fnDraw(false);
                      $("#btn-save").html('Submit');
                      $("#btn-save"). attr("disabled", false);
                      $("#user_id").val('');
                      $('.display_message').html(data.message).css("color", "green").show().fadeOut(5000); 
                    },
                    error: function(data,xhr){
                      $('#validation-errors').html('');
                      if( data.status == 422 ) {
                        var errors =   $.parseJSON(data.responseText);
                        $.each(errors, function(key,value) {
                          $('#validation-errors').addClass("alert alert-danger");

                            if($.isPlainObject(value)) {
                                $.each(value, function (key, value) {                       
                                    console.log(key+ " " +value);
                                $('#validation-errors').show().append(value+"<br/>");

                                });
                            }else{
                            $('#validation-errors').show().append(value+"<br/>"); 
                            }
                        }); 
                      }

                      $('.display_message').html(data.message).css("color", "red").fadeOut(5000); 
                    }
              });

    }  
  });  
 
</script>
</html>