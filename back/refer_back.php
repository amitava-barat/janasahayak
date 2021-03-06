<?php
header("X-XSS-Protection: 1;mode = block");
header("X-Content-Type-Options: nosniff");
include("../inc/dblib.inc.php");
$conn = OpenDB();

$tag = isset($_POST['tag']) ? $_POST['tag'] : '';
?>


<?php
/*-------------- insert into trans mas --------------------*/
if(($tag=='REF-NOTE'))
{	
	
	$myid = isset($_POST['myid']) ? $_POST['myid'] : '';
	$hid_uid = isset($_POST['hid_uid']) ? $_POST['hid_uid'] : '';
	
    $sql_search=" select flt_id,rmn,dist_id,dkt_no from flt_mas where ";
	$sql_search.=" md5(flt_id)=:myid ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':myid', $myid);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$rmn=$row_search['rmn'];
	$dkt_no=$row_search['dkt_no'];

?>
 <link rel="stylesheet" href="./plugins/select2/select2.min.css">

<style>
.modal-body
{
	height:95px !important;
}
.modal-header,.modal-body,.modal-footer,.modal-content,.modal-dialog
{
	
	width:905px !important;
}
.modal-body .select2
{
	margin-bottom: 5px;
}
</style>
<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="./plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="./dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="./plugins/timepicker/bootstrap-timepicker.min.css">
<form method="POST" enctype="multipart/form-data" id="fileUploadForm">
    <div class="example-modal">
        <div class="modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="batch-close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Docket Update of Mobile No: <?php echo $rmn; ?> & Docket No: <?php echo $dkt_no; ?></h4>
                <input type="hidden" id="hid_fault" name="hid_fault" value="<?php echo $myid; ?>">
                <input type="hidden" id="hid_uid"  name="hid_uid" value="<?php echo $hid_uid; ?>">
              </div>
              <div class="modal-body">
                <div class="col-md-6">
                 <div class="form-group">
                  <label for="Refer To" class="col-sm-4" >Refer To</label>
                  <div class="col-sm-8">
	                  <select class="form-control select2 " name="refer_to"  id="refer_to" style=" width: 100%; padding-top:-3px;" tabindex="1">
					    <option value="" selected="selected">Select Refer To</option>
					    <?php
					      $sql="select um.uid,um.user_nm,dm.de_eng ";
					      $sql.="from user_mas um,dept_mas dm ";
					      $sql.="where um.dept_id=dm.dept_id ";
					      $sql.=" order by um.dept_id";
					      $sth = $conn->prepare($sql);
					      $sth->execute();
					      $ss=$sth->setFetchMode(PDO::FETCH_ASSOC);
					      $row = $sth->fetchAll();
					      foreach ($row as $key => $value) 
					      {
					        $uid=$value['uid'];
					        $user_nm=$value['user_nm'];
					        $dept_nm=$value['de_eng'];
					        ?>
					        <option value="<?php echo $uid; ?>"><?php echo "$user_nm ($dept_nm)"; ?></option>
					        <?php
					      }
					      ?>
					 </select>
                  </div>
          		</div>
          		<div class="form-group">
	                <label for="Photo" class="col-sm-4" >Photo</label>
	                <div class="col-sm-8">
	                   <input  id="photo" type="file" accept="image/gif, image/jpeg, image/png" onchange="readURL(this);" class="form-control" tabindex="3" >
                        <input id="base" name="base" type="text" class="form-control" readonly="readonly" style="visibility:hidden;" />
	                </div>
          		  </div>   
                </div>
                <div class="col-md-6">
                  <div class="form-group">
	                <label for="Remarks" class="col-sm-4" >Remarks</label>
	                <div class="col-sm-8">
	                	<textarea name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks" tabindex="2" rows="3"></textarea>
	                </div>
          		  </div>  
          		</div>
              </div>
              <div class="modal-footer">
               <input type="button" name="doc_submit" id="doc_submit" class="btn btn-primary pull-right" value="Submit" tabindex="4">
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
        </div>
      </div>
      </form>    
 <script type="text/javascript" src="./lib/jquery-1.11.1.js"></script>
 <script src="./bootstrap/js/bootstrap.min.js"></script>
 <script src="./plugins/select2/select2.full.min.js"></script>
<script src="./plugins/input-mask/jquery.inputmask.js"></script>
<script src="./plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="./plugins/input-mask/jquery.inputmask.extensions.js"></script>  
<script>
function readURL(input) {
  var FileSize = input.files[0].size / 1024 / 1024; // in MB
  if (FileSize > 2) 
  {
        alert('File size exceeds 2 MB');
        $(input).val('');
  } 
  else 
  {      
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#photo').attr('src', e.target.result);
        
        $('#base').val(e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
}
$(document).ajaxStart(function ()
{
  $('body').addClass('wait');
   
})
.ajaxComplete(function () {

  $('body').removeClass('wait');

});

$(function () {
$(".close").click(function(){
	modal.style.display = "none";
});
});
$(function () {
    $('.select2').select2()
    $("#col_date").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    
  });



$( "#doc_submit").click(function() {

	var refer_to = $("#refer_to").val();
	var photo = $("#photo").val();
	var base = $("#base").val();
	var remarks = $("#remarks").val();	
	var hid_fault = $("#hid_fault").val();
	var hid_uid = $("#hid_uid").val();	
	
	if(remarks!="")
    {
       if(/^[/!<>]+$/.test(remarks))
       {
       
	       alertify.error("  </!()<> are not supported");
	       $('#remarks').css("border-color","#FF0000");
	       $('#remarks').focus();
	       return false;
       }
    }
	  
	var fileinput =$('#photo').val();
   
    if(fileinput!= "")
    {
	    var filearr=fileinput.split('.');
	    if(filearr.length>2)
	     {
	      alert('Double extension files are not allowed.'); 
	      $('#photo').focus();
	      return false;    
	    }
	    if(fileinput!="")
	    {
	        var extension = fileinput.substr(fileinput.lastIndexOf('.') + 1).toLowerCase(); 
	        var allowedExtensions = ['jpg', 'jpeg', 'png'];
	        if (fileinput.length > 0) 
	        { 
	          if (allowedExtensions.indexOf(extension) === -1) 
	          { 
	            alert('Invalid file Format. Only ' + allowedExtensions.join(', ') + ' are allowed.'); 
	            $('#photo').focus();
	            return false; 
	          } 
	        }
	    }
	}

	var request = $.ajax({
    url: "./back/refer_back.php",
    method: "POST",
    data: {refer_to: refer_to,base:base,remarks:remarks,
    	hid_fault:hid_fault,hid_uid:hid_uid,tag: 'REF-TRAN'  },
    dataType: "html",
    success:function(msg) {
    alert('Docket Update Successfully');
    location.reload();

  },
  error: function(xhr, status, error) {
            alert(status);
            alert(xhr.responseText);
        },
  }); 
	  modal.style.display = "none";
  });	
</script>
<?php
}
?>
<?php
/*-------------- insert into trans mas --------------------*/
if(($tag=='REF-TRAN'))
{
	
	$refer_to= $_POST['refer_to'];
	$base= $_POST['base'];
	$remarks= $_POST['remarks'];
	$hid_fault= $_POST['hid_fault']; 
	$hid_uid= $_POST['hid_uid'];
	if(empty($refer_to))
	{
		$refer_to=$hid_uid;
	}
    
    $sql_search=" select flt_id,rmn,dist_id,dkt_no from flt_mas where ";
	$sql_search.=" md5(flt_id)=:hid_fault ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':hid_fault', $hid_fault);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$flt_id=$row_search['flt_id'];
	$rmn=$row_search['rmn'];
	$dist_id=$row_search['dist_id'];
	$dkt_no=$row_search['dkt_no'];
	
	/*-------------- insert data for refer_mas-----------------------------*/
	$sql ="insert into refer_mas (";
	$sql.="flt_id,dist_id,rmn,dkt_no,refer_doc,remarks,refer_by ";
	$sql.=" ,refer_to,refer_date) values ( ";
	$sql.=" :flt_id,:dist_id,:rmn,:dkt_no,:base ";
	$sql.=" ,trim(:remarks) ,trim(:hid_uid),:refer_to,current_timestamp) ";
	$sth = $conn->prepare($sql);
	$sth->bindParam(':flt_id', $flt_id);
	$sth->bindParam(':dist_id', $dist_id);
	$sth->bindParam(':rmn', $rmn);
	$sth->bindParam(':dkt_no', $dkt_no);
	$sth->bindParam(':base', $base);
	$sth->bindParam(':remarks', addslashes($remarks));
	$sth->bindParam(':hid_uid', $hid_uid);
	$sth->bindParam(':refer_to', $refer_to);
	$sth->execute();

	$sqlU=" update  flt_mas set refer_to=:refer_to,refer_date=current_timestamp,refer_by=:hid_uid ";
    $sqlU.=",refer_rmk=:remarks where flt_id=:flt_id ";
    $sthU = $conn->prepare($sqlU);
    $sthU->bindParam(':refer_to', $refer_to);
    $sthU->bindParam(':flt_id', $flt_id);
    $sthU->bindParam(':remarks', $remarks);
    $sthU->bindParam(':hid_uid', $hid_uid);
    $sthU->execute();
		
}
?>
<?php
/*-------------- insert into trans mas --------------------*/
if(($tag=='SHOW-PHOTO'))
{	
	
	$img = isset($_POST['img']) ? $_POST['img'] : '';
	$img_name = isset($_POST['img_name']) ? $_POST['img_name'] : '';

	$sql_search=" select flt_id,rmn,comp_img,dkt_no from flt_mas where ";
	$sql_search.=" md5(flt_id)=:img ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':img', $img);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$flt_id=$row_search['flt_id'];
	$rmn=$row_search['rmn'];
	$dkt_no=$row_search['dkt_no'];
	$comp_img=$row_search['comp_img'];

    
?>
<style type="text/css">
	.modal
	{
		padding-top: 10px !important;

	}
</style>
<form method="POST" enctype="multipart/form-data" id="fileUploadForm">
    <div class="example-modal">
        <div class="modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="batch-close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Docket No: <?php echo $dkt_no; ?></h4>
              </div>
              <div class="modal-body">
                <img src="<?php echo $comp_img; ?>" style="width: 100%; height: auto;">
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
        </div>
      </div>
      </form>  
      <script type="text/javascript">
      	$(function () {
			$(".close").click(function(){
				modal.style.display = "none";
			});
		});
      </script>
     
<?php
}
?>

<?php
/*-------------- insert into trans mas --------------------*/
if($tag=="DOK-INFO")
{	
	
	$myid = isset($_POST['myid']) ? $_POST['myid'] : '';
	$hid_uid = isset($_POST['hid_uid']) ? $_POST['hid_uid'] : '';
	
    $sql_search=" select flt_id,rmn,dist_id,dkt_no,dkt_date from flt_mas where ";
	$sql_search.=" md5(flt_id)=:myid ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':myid', $myid);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$rmn=$row_search['rmn'];
	$dkt_no=$row_search['dkt_no'];
	$dkt_date1=$row_search['dkt_date'];
	//echo $dkt_date1;
	$dkt_date=british_to_ansi(substr($dkt_date1,0,10));

?>
 <style>
.modal-body
{
	height:auto !important;
}
.modal-header,.modal-body,.modal-footer,.modal-content,.modal-dialog
{
	
	width:905px !important;
}

</style>
<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="./plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="./dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="./plugins/timepicker/bootstrap-timepicker.min.css">
<form method="POST" enctype="multipart/form-data" id="fileUploadForm">
    <div class="example-modal">
    	 <div class="modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="batch-close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Docket Update of Mobile No: <?php echo $rmn; ?> & Docket No: <?php echo $dkt_no; ?> & Date <?php echo $dkt_date; ?></h4>
                <input type="hidden" id="hid_fault" name="hid_fault" value="<?php //echo $myid; ?>">
                <input type="hidden" id="hid_uid"  name="hid_uid" value="<?php // echo $hid_uid; ?>">
              </div>
              <div class="modal-body">
              	<table  class="table table-bordered table-striped" >
					<thead>
						<tr>
							<th>Refer Date</th>
							<th>Refer By</th>
							<th>Refer To</th>
							<th>Remarks</th>
							<th>Image</th>
						</tr>
					</thead>
	              	<?php
	               	$sqlR="select r.refer_id,r.refer_date,r.remarks,r.refer_to,u.user_nm,r.refer_doc from refer_mas r, user_mas u ";
					$sqlR.="WHERE dkt_no=:dkt_no and r.refer_by=u.uid ";
					$sqlR.=" ORDER BY refer_id DESC ";
					$sth_search = $conn->prepare($sqlR);
					$sth_search->bindParam(':dkt_no', $dkt_no);
					$sth_search->execute();
					$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
					$rowR = $sth_search->fetchAll();
					foreach ($rowR as $key => $valueR) 
					{	
						$refer_id=$valueR['refer_id'];
						$refer_date1=$valueR['refer_date'];
						$ref_from=$valueR['user_nm'];
						$remarks=$valueR['remarks'];
						$refer_to=$valueR['refer_to'];
						$refer_doc=$valueR['refer_doc'];
						$refer_date=british_to_ansi(substr($refer_date1,0,10));
						
						$sqlR="select user_nm from user_mas WHERE 1=1 ";
						$sqlR.=" and uid=:refer_to ";
						$sth_search = $conn->prepare($sqlR);
						$sth_search->bindParam(':refer_to', $refer_to);
						$sth_search->execute();
						$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
						$row_search = $sth_search->fetch();
						$ref_to=$row_search['user_nm'];
						?>
						<tr> 
							<td><?php echo $refer_date;?></td>
							<td><?php echo $ref_from;?></td>
							<td><?php echo $ref_to;?></td>
							<td><?php echo $remarks;?></td>
							<td align="center">
							<?php if(!empty($refer_doc))
						    {
						        ?>
						        <a href="javascript:void(0);" class="imageresource" id="<?php echo md5($refer_id); ?>" alt="<?php echo $ref_from; ?>" title="<?php echo $dkt_no;?>">
						          <i class="fa fa-photo" aria-hidden="true" ></i>
						        </a>	
						        <?php
						    }
						    ?>
							</td>
						</tr>
						<?php	
					}
                    ?>
                   </table>
              </div>
              <div class="modal-footer">
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
        </div>
      </div>
    </form>    
 
<script>
$(function () {
$(".close").click(function(){
	modal.style.display = "none";
});
});
$( ".imageresource").click(function() {
	
    var img =$(this).attr("id");
    var img_name =$(this).attr("alt");
    var request = $.ajax({
    url: "./back/refer_back.php",
    method: "POST",
    data: {img: img,img_name:img_name,tag: 'SHOW-PHOTO2'  },
    dataType: "html",
    success:function(msg) {
    $("#myModal").html(msg);  

  },
  error: function(xhr, status, error) {
            alert(status);
            alert(xhr.responseText);
        },
  }); 
   // modal.style.display = "block";
	
}); 
</script>
<style>
.modal {
display: none; /* Hidden by default */
position: fixed; /* Stay in place */
z-index: 1000; /* Sit on top */
padding-top: 100px; /* Location of the box */
left: 0;
top: 0;
width: 100%; /* Full width */
height: 100%; /* Full height */
overflow: auto; /* Enable scroll if needed */
background-color: rgb(0,0,0); /* Fallback color */
background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
.example-modal .modal {
position: relative;
top: auto;
bottom: auto;
right: auto;
left: auto;
display: block;
z-index: 1;
}

.example-modal .modal {
background: transparent !important;
}

</style>
<div id="myModal" class="modal">

</div>

<?php
}
?>
<?php
/*-------------- insert into trans mas --------------------*/
if(($tag=='SHOW-PHOTO2'))
{	
	
	$img = isset($_POST['img']) ? $_POST['img'] : '';
	$img_name = isset($_POST['img_name']) ? $_POST['img_name'] : '';

	$sql_search=" select r.refer_doc,r.dkt_no,u.user_nm,f.dkt_date ";
	$sql_search.=" from  refer_mas r,user_mas u, flt_mas f where ";
	$sql_search.=" md5(r.refer_id)=:img and r.refer_by=u.uid and r.dkt_no=f.dkt_no ";
	$sth_search = $conn->prepare($sql_search);
	$sth_search->bindParam(':img', $img);
	$sth_search->execute();
	$ss_search=$sth_search->setFetchMode(PDO::FETCH_ASSOC);
	$row_search = $sth_search->fetch();
	$flt_id=$row_search['flt_id'];
	$rmn=$row_search['rmn'];
	$dkt_no=$row_search['dkt_no'];
	$refer_doc=$row_search['refer_doc'];
	$user_nm=$row_search['user_nm'];
	$dkt_date1=$row_search['dkt_date'];
	$dkt_date=british_to_ansi(substr($dkt_date1,0,10));
    
?>
<style type="text/css">
	.modal
	{
		padding-top: 10px !important;

	}
</style>
<form method="POST" enctype="multipart/form-data" id="fileUploadForm">
    <div class="example-modal">
        <div class="modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="batch-close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Docket No: <?php echo $dkt_no; ?>, Date <?php echo $dkt_date; ?> & Upload By <?php echo $user_nm; ?></h4>
              </div>
              <div class="modal-body">
                <img src="<?php echo $refer_doc; ?>" style="width: 100%; height: auto;">
                
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
        </div>
      </div>
      </form>  
      <script type="text/javascript">
      	$(function () {
			$(".close").click(function(){
				modal.style.display = "none";
			});
		});
      </script>
     
<?php
}
?>
<?php
$conn=null;
?>