<?php 
header("X-XSS-Protection: 1;mode = block");
include('./header.php'); ?>
<?php
$hr_id = isset($_REQUEST['hr_id']) ? $_REQUEST['hr_id'] : ''; 

$sql ="select uid,user_type,dist_id,user_nm,user_id ";
$sql.=",dept_id,comp_type_id,user_status,sub_div_id,block_id,ps_id ";
$sql.="from user_mas where md5(uid)=:hr_id ";
$sth = $conn->prepare($sql);
$sth->bindParam(':hr_id', $hr_id);
$sth->execute();
$sth->setFetchMode(PDO::FETCH_ASSOC);
$row = $sth->fetch();
$s_uid=$row['uid'];
$s_user_type=$row['user_type'];
$s_user_nm=$row['user_nm'];
$s_user_id=$row['user_id'];
$s_dept_id=$row['dept_id'];
$s_comp_type_id=$row['comp_type_id'];
$s_user_status=$row['user_status'];
$s_dist_id=$row['dist_id'];
$s_sub_div_id=$row['sub_div_id'];
$s_block_id=$row['block_id'];
$s_ps_id=$row['ps_id'];

$s_comp_type_id=explode(',',$s_comp_type_id);
//echo $s_dept_id;


if(!empty($hr_id)) 
{

?>
<div class="row">
          <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header  with-border">
                    <h3 class="box-title">User Details</h3>
                </div>
                <form name="form1"  method="post" class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate()">
                  <input type="hidden" id="hid_token" value="<?php echo $ses_token; ?>"/>
                  <input type="hidden" id="hid_uid" value="<?php echo $hr_id; ?>"/>
                  <input type="hidden" id="hid_log_user" value="<?php echo $ses_uid; ?>"/>

                    <div class="box-body">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="Lessee" class="col-sm-4">Department<font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                           <?php
                                $sqle ="select dept_id,de_eng ";
                                $sqle.="from dept_mas WHERE dist_id='1' ";
                                $sthc =$conn->prepare($sqle);
                                $sthc->execute();
                                $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                                $rowc = $sthc->fetchAll();
							?>
                             <select name="dept" id="dept" class="form-control select2"  style="width: 100%;" tabindex="1" autofocus="autofocus">
                                  <option value="" >Select Department</option>
                                  <?php
                                   foreach ($rowc as $keyc => $rowe) 
                                	{
										$e_dept_id=$rowe['dept_id'];
										$e_de_eng=$rowe['de_eng'];
                                    ?>	
                                    <option value="<?php echo $e_dept_id; ?>" <?php if($s_dept_id== $e_dept_id) { echo "SELECTED"; } ?>><?php echo $e_de_eng; ?></option>
                                    <?php
                                }
                                ?>
                                  
                             </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="Lessee" class="col-sm-4">Complaign Type<font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                           <?php
                            $sqle ="select comp_type_id,comp_type_eng ";
                            $sqle.="from compl_type_mas ";
                            $sthc =$conn->prepare($sqle);
                            $sthc->execute();
                            $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                                $rowc = $sthc->fetchAll();
							                ?>
                             <select name="comp_type" id="comp_type" class="form-control select2" multiple="multiple"  style="width: 100%;" tabindex="1" autofocus="autofocus">
                                  <option value="" >Select Complaign Type</option>
                                  <?php
                                   foreach ($rowc as $keyc => $rowe) 
                                	{
                										$e_comp_type_id=$rowe['comp_type_id'];
                										$e_comp_type_eng=$rowe['comp_type_eng'];
                                    ?>	
                                    <option value="<?php echo $e_comp_type_id; ?>" <?php if(in_array($e_comp_type_id, $s_comp_type_id)) { echo "SELECTED"; } ?>><?php echo $e_comp_type_eng; ?></option>
                                    <?php
                                }
                                ?>
                                  
                             </select>
                          </div>
                        </div>
                        
                        <div class="form-group  has-feedback">
                           <label for="User Name" class="col-sm-4">User Name<font color="#FF0000">*</font></label>
                           <div class="col-sm-8">
                             <input type="text" name="user_nm" id="user_nm" class="form-control" placeholder="User Name" value="<?php echo $s_user_nm; ?>" tabindex="5">
                             <span class="glyphicon glyphicon-user form-control-feedback"></span>
                           </div>
                        </div>
    					<div class="form-group  has-feedback">
                           <label for="User ID" class="col-sm-4">User ID<font color="#FF0000">*</font></label>
                           <div class="col-sm-8">
                              <input type="text" name="user_id" id="user_id" class="form-control" placeholder="User ID" readonly value="<?php echo $s_user_id; ?>" tabindex="6">
                              
                           </div>
                        </div>   
                        <div class="form-group  has-feedback">
                           <label for="pwd" class="col-sm-4">Password<font color="#FF0000">*</font></label>
                           <div class="col-sm-8">
                              <input type="password" name="pwd" id="pwd" class="form-control" placeholder="Password" tabindex="6">
                              
                           </div>
                        </div>                 
                                   
                        
                        <div class="form-group">
                          <label for="User Status" class="col-sm-4">User Type<font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                             <select name="user_type" id="user_type" class="form-control select2"  style="width: 100%;" tabindex="13" autofocus="autofocus">
                                  <option value="D" <?php if($s_user_type=='D') { echo "SELECTED"; } ?>>District</option>
                                  <option value="B" <?php if($s_user_type=='B') { echo "SELECTED"; } ?> >Other</option>
                             </select>
                          </div>
                        </div>    
                        
                      </div>
                      <div class="col-md-6"> 
                        <div class="form-group">
                          <label for="District" class="col-sm-4">District &nbsp;<font color="#FF0000">*</font></label>
                          <div class="col-sm-8" id="div_challan">
                             <select name="district" id="district" class="form-control select2"  style="width: 100%;" tabindex="2">
                               <?php
                                $sqle=" select dist_id,dist_nm_eng ";
                                $sqle.=" from district_mas ";
                                $sthc = $conn->prepare($sqle);
                                $sthc->execute();
                                $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                                $rowc = $sthc->fetchAll();
                                foreach ($rowc as $keyc => $rowe) 
                                {
                                    $e_dist_id=$rowe['dist_id'];
                                    $e_dist_nm_eng=$rowe['dist_nm_eng'];
                                    ?>	
                                    <option value="<?php echo $e_dist_id; ?>" <?php if($s_dist_id== $e_dist_id) { echo "SELECTED"; } ?>><?php echo $e_dist_nm_eng; ?></option>
                                    <?php
                                }
                                ?>
                             </select>
                          </div>
                        </div>
                        
                        <div class="form-group">
                              <label for="Block" class="col-sm-4">Sub Division</label>
                               <div class="col-sm-8"  id="div_block">
                                 <select name="sub_division" id="sub_division" class="form-control select2"  style="width: 100%;" tabindex="3">
                                   <option value="">Sub Division</option>
                                   <?php
                                    $sqle=" select sub_div_id,sub_div_nm ";
                                    $sqle.=" from sub_div_mas WHERE 1=1  ";
                                    $sthc = $conn->prepare($sqle);
									$sthc->bindParam(':dist_id', $dist_id);
                                    $sthc->execute();
                                    $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                                    $rowc = $sthc->fetchAll();
                                    foreach ($rowc as $keyc => $rowe) 
                                    {
                                        $e_sub_div_id=$rowe['sub_div_id'];
                                        $e_sub_div_nm=$rowe['sub_div_nm'];
                                        ?>	
                                        <option value="<?php echo $e_sub_div_id; ?>" <?php if($s_sub_div_id==$e_sub_div_id) { echo "SELECTED"; } ?>><?php echo $e_sub_div_nm; ?></option>
                                        <?php
                                    }
                                    ?>
                                 </select>
                               </div> 
							</div>
                         <div class="form-group">
                               <label for="Block" class="col-sm-4">Block</label>
                               <div class="col-sm-8"  id="div_block">
                                 <select name="block" id="block" class="form-control select2"  style="width: 100%;" tabindex="3">
                                   <option value="">Select Block</option>
                                   <?php
                                    $sqle=" select block_id,block_nm ";
                                    $sqle.=" from block_mas WHERE 1=1  ";
                                    $sthc = $conn->prepare($sqle);
									$sthc->bindParam(':dist_id', $dist_id);
                                    $sthc->execute();
                                    $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                                    $rowc = $sthc->fetchAll();
                                    foreach ($rowc as $keyc => $rowe) 
                                    {
                                        $e_block_id=$rowe['block_id'];
                                        $e_block_nm=$rowe['block_nm'];
                                        ?>	
                                        <option value="<?php echo $e_block_id; ?>" <?php if($s_block_id==$e_sub_div_id) { echo "SELECTED"; } ?>><?php echo $e_block_nm; ?></option>
                                        <?php
                                    }
                                    ?>
                                 </select>
                               </div> 
							</div> 
                            
                            <div class="form-group">
                               <label for="Block" class="col-sm-4">Police Station</label>
                               <div class="col-sm-8"  id="div_ps">
                                 <select name="ps" id="ps" class="form-control select2"  style="width: 100%;" tabindex="3">
                                   <option value="">Select Police Station</option>
                                   <?php
                                    $sqle=" select ps_id,ps_nm ";
                                    $sqle.=" from ps_mas WHERE 1=1  ";
                                    $sthc = $conn->prepare($sqle);
									$sthc->bindParam(':dist_id', $dist_id);
                                    $sthc->execute();
                                    $ssc=$sthc->setFetchMode(PDO::FETCH_ASSOC);
                                    $rowc = $sthc->fetchAll();
                                    foreach ($rowc as $keyc => $rowe) 
                                    {
                                        $e_ps_id=$rowe['ps_id'];
                                        $e_ps_nm=$rowe['ps_nm'];
                                        ?>	
                                        <option value="<?php echo $e_ps_id; ?>" <?php if($s_ps_id==$e_ps_id) { echo "SELECTED"; } ?>><?php echo $e_ps_nm; ?></option>
                                        <?php
                                    }
                                    ?>
                                 </select>
                               </div> 
						              	</div> 
                            <div class="form-group">
                          <label for="User Status" class="col-sm-4">User Status<font color="#FF0000">*</font></label>
                          <div class="col-sm-8">
                             <select name="user_status" id="user_status" class="form-control select2"  style="width: 100%;" tabindex="13" autofocus="autofocus">
                                  <option value="A" <?php if($s_user_status=='A') { echo "SELECTED"; } ?> >Active</option>
                                  <option value="D" <?php if($s_user_status=='D') { echo "SELECTED"; } ?>>Deactive</option>
                             </select>
                          </div>
                        </div> 
                        
                      </div>
                     
                      <div id="info"></div>
                  </div>
                  <div class="box-footer">
                   <a href="<?php echo $full_url; ?>/index.php"  class="btn btn-default">Cancel</a>
                    <input type="button" name="edit" id="edit" class="btn btn-primary pull-right" value="Submit" tabindex="13">
                  </div>
                </form>
            </div>
         </div>
       </div>   
       <script src="<?php echo $full_url; ?>/js/user.js"></script>
   
<?php 
}
include('./footer.php'); ?>     
