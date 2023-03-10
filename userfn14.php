<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}
if ((CurrentUserLevel() == 1)) {
		 $_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyNewCount']      = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyReviewCount']   = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyResolutionCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (7) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
}
/*if ((CurrentUserLevel() == 1) || (CurrentUserLevel() == 2)|| (CurrentUserLevel() == 3)) {
		 $_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		 $_SESSION['MyNewCount']      = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
}*/
if (CurrentUserLevel() == -1) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
	$_SESSION['MyReviewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6) ");
	$_SESSION['MyResolutionCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (7) ");
}
/*if (CurrentUserLevel() == 1) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) ");
}*/
if (CurrentUserLevel() == 2) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (3) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyReviewCount']   = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6) ");
	$_SESSION['MyResolutionCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (7) ");
}
if (CurrentUserLevel() == 3) {
	$_SESSION['MyApprovedCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (5) ");
	$_SESSION['MyReworkCount']  = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (1) ");
	$_SESSION['MyPendingCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (4) ");
	$_SESSION['MyNewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (0) AND `departments` = '".$_SESSION['Department']."' ");
	$_SESSION['MyReviewCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (6) ");
	$_SESSION['MyResolutionCount'] = ew_ExecuteScalar("SELECT COUNT(id) FROM `report_form` WHERE `status` in (7) ");
}

function generateSIDKey(){
$randStrs =	mt_rand(001,999);
return "SID".$randStrs;
}

function generateINCKey(){
$randStrs =	mt_rand(10000000,99999999);
return "INC".$randStrs;
}

function generateSTNKey(){
$randStrs =	mt_rand(001,999);
return "STN".$randStrs;
}

//
//$conn = mysqli_connect("localhost", "root","","incident_report");

?>
