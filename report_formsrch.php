<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "report_forminfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$report_form_search = NULL; // Initialize page object first

class creport_form_search extends creport_form {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'report_form';

	// Page object name
	var $PageObjName = 'report_form_search';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (report_form)
		if (!isset($GLOBALS["report_form"]) || get_class($GLOBALS["report_form"]) == "creport_form") {
			$GLOBALS["report_form"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["report_form"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'report_form', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (users)
		if (!isset($UserTable)) {
			$UserTable = new cusers();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate(ew_GetUrl("changepwd.php"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanSearch()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("report_formlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->id->Visible = FALSE;
		$this->datetime_initiated->SetVisibility();
		$this->incident_id->SetVisibility();
		$this->staffid->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->department->SetVisibility();
		$this->branch->SetVisibility();
		$this->departments->SetVisibility();
		$this->category->SetVisibility();
		$this->sub_category->SetVisibility();
		$this->start_date->SetVisibility();
		$this->end_date->SetVisibility();
		$this->duration->SetVisibility();
		$this->amount_paid->SetVisibility();
		$this->no_of_people_involved->SetVisibility();
		$this->incident_type->SetVisibility();
		$this->incident_category->SetVisibility();
		$this->incident_location->SetVisibility();
		$this->incident_description->SetVisibility();
		$this->_upload->SetVisibility();
		$this->status->SetVisibility();
		$this->initiator_action->SetVisibility();
		$this->initiator_comment->SetVisibility();
		$this->report_by->SetVisibility();
		$this->datetime_resolved->SetVisibility();
		$this->resolved_action->SetVisibility();
		$this->resolved_comment->SetVisibility();
		$this->resolved_by->SetVisibility();
		$this->datetime_approved->SetVisibility();
		$this->approval_action->SetVisibility();
		$this->approval_comment->SetVisibility();
		$this->approved_by->SetVisibility();
		$this->closure_action->SetVisibility();
		$this->closure_comment->SetVisibility();
		$this->last_updated_date->SetVisibility();
		$this->last_updated_by->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $report_form;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($report_form);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "report_formview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "report_formlist.php" . "?" . $sSrchStr;
						$this->Page_Terminate($sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->id); // id
		$this->BuildSearchUrl($sSrchUrl, $this->datetime_initiated); // datetime_initiated
		$this->BuildSearchUrl($sSrchUrl, $this->incident_id); // incident_id
		$this->BuildSearchUrl($sSrchUrl, $this->staffid); // staffid
		$this->BuildSearchUrl($sSrchUrl, $this->staff_id); // staff_id
		$this->BuildSearchUrl($sSrchUrl, $this->department); // department
		$this->BuildSearchUrl($sSrchUrl, $this->branch); // branch
		$this->BuildSearchUrl($sSrchUrl, $this->departments); // departments
		$this->BuildSearchUrl($sSrchUrl, $this->category); // category
		$this->BuildSearchUrl($sSrchUrl, $this->sub_category); // sub_category
		$this->BuildSearchUrl($sSrchUrl, $this->start_date); // start_date
		$this->BuildSearchUrl($sSrchUrl, $this->end_date); // end_date
		$this->BuildSearchUrl($sSrchUrl, $this->duration); // duration
		$this->BuildSearchUrl($sSrchUrl, $this->amount_paid); // amount_paid
		$this->BuildSearchUrl($sSrchUrl, $this->no_of_people_involved); // no_of_people_involved
		$this->BuildSearchUrl($sSrchUrl, $this->incident_type); // incident_type
		$this->BuildSearchUrl($sSrchUrl, $this->incident_category); // incident-category
		$this->BuildSearchUrl($sSrchUrl, $this->incident_location); // incident_location
		$this->BuildSearchUrl($sSrchUrl, $this->incident_description); // incident_description
		$this->BuildSearchUrl($sSrchUrl, $this->_upload); // upload
		$this->BuildSearchUrl($sSrchUrl, $this->status); // status
		$this->BuildSearchUrl($sSrchUrl, $this->initiator_action); // initiator_action
		$this->BuildSearchUrl($sSrchUrl, $this->initiator_comment); // initiator_comment
		$this->BuildSearchUrl($sSrchUrl, $this->report_by); // report_by
		$this->BuildSearchUrl($sSrchUrl, $this->datetime_resolved); // datetime_resolved
		$this->BuildSearchUrl($sSrchUrl, $this->resolved_action); // resolved_action
		$this->BuildSearchUrl($sSrchUrl, $this->resolved_comment); // resolved_comment
		$this->BuildSearchUrl($sSrchUrl, $this->resolved_by); // resolved_by
		$this->BuildSearchUrl($sSrchUrl, $this->datetime_approved); // datetime_approved
		$this->BuildSearchUrl($sSrchUrl, $this->approval_action); // approval_action
		$this->BuildSearchUrl($sSrchUrl, $this->approval_comment); // approval_comment
		$this->BuildSearchUrl($sSrchUrl, $this->approved_by); // approved_by
		$this->BuildSearchUrl($sSrchUrl, $this->closure_action); // closure_action
		$this->BuildSearchUrl($sSrchUrl, $this->closure_comment); // closure_comment
		$this->BuildSearchUrl($sSrchUrl, $this->last_updated_date); // last_updated_date
		$this->BuildSearchUrl($sSrchUrl, $this->last_updated_by); // last_updated_by
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = $Fld->FldParm();
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = $FldVal;
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = $FldVal2;
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = $objForm->GetValue("x_id");
		$this->id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id");

		// datetime_initiated
		$this->datetime_initiated->AdvancedSearch->SearchValue = $objForm->GetValue("x_datetime_initiated");
		$this->datetime_initiated->AdvancedSearch->SearchOperator = $objForm->GetValue("z_datetime_initiated");

		// incident_id
		$this->incident_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_incident_id");
		$this->incident_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_incident_id");

		// staffid
		$this->staffid->AdvancedSearch->SearchValue = $objForm->GetValue("x_staffid");
		$this->staffid->AdvancedSearch->SearchOperator = $objForm->GetValue("z_staffid");

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_staff_id");
		$this->staff_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_staff_id");

		// department
		$this->department->AdvancedSearch->SearchValue = $objForm->GetValue("x_department");
		$this->department->AdvancedSearch->SearchOperator = $objForm->GetValue("z_department");

		// branch
		$this->branch->AdvancedSearch->SearchValue = $objForm->GetValue("x_branch");
		$this->branch->AdvancedSearch->SearchOperator = $objForm->GetValue("z_branch");

		// departments
		$this->departments->AdvancedSearch->SearchValue = $objForm->GetValue("x_departments");
		$this->departments->AdvancedSearch->SearchOperator = $objForm->GetValue("z_departments");

		// category
		$this->category->AdvancedSearch->SearchValue = $objForm->GetValue("x_category");
		$this->category->AdvancedSearch->SearchOperator = $objForm->GetValue("z_category");

		// sub_category
		$this->sub_category->AdvancedSearch->SearchValue = $objForm->GetValue("x_sub_category");
		$this->sub_category->AdvancedSearch->SearchOperator = $objForm->GetValue("z_sub_category");

		// start_date
		$this->start_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_start_date");
		$this->start_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_start_date");

		// end_date
		$this->end_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_end_date");
		$this->end_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_end_date");

		// duration
		$this->duration->AdvancedSearch->SearchValue = $objForm->GetValue("x_duration");
		$this->duration->AdvancedSearch->SearchOperator = $objForm->GetValue("z_duration");

		// amount_paid
		$this->amount_paid->AdvancedSearch->SearchValue = $objForm->GetValue("x_amount_paid");
		$this->amount_paid->AdvancedSearch->SearchOperator = $objForm->GetValue("z_amount_paid");

		// no_of_people_involved
		$this->no_of_people_involved->AdvancedSearch->SearchValue = $objForm->GetValue("x_no_of_people_involved");
		$this->no_of_people_involved->AdvancedSearch->SearchOperator = $objForm->GetValue("z_no_of_people_involved");

		// incident_type
		$this->incident_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_incident_type");
		$this->incident_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_incident_type");

		// incident-category
		$this->incident_category->AdvancedSearch->SearchValue = $objForm->GetValue("x_incident_category");
		$this->incident_category->AdvancedSearch->SearchOperator = $objForm->GetValue("z_incident_category");

		// incident_location
		$this->incident_location->AdvancedSearch->SearchValue = $objForm->GetValue("x_incident_location");
		$this->incident_location->AdvancedSearch->SearchOperator = $objForm->GetValue("z_incident_location");

		// incident_description
		$this->incident_description->AdvancedSearch->SearchValue = $objForm->GetValue("x_incident_description");
		$this->incident_description->AdvancedSearch->SearchOperator = $objForm->GetValue("z_incident_description");

		// upload
		$this->_upload->AdvancedSearch->SearchValue = $objForm->GetValue("x__upload");
		$this->_upload->AdvancedSearch->SearchOperator = $objForm->GetValue("z__upload");

		// status
		$this->status->AdvancedSearch->SearchValue = $objForm->GetValue("x_status");
		$this->status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_status");

		// initiator_action
		$this->initiator_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_initiator_action");
		$this->initiator_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_initiator_action");

		// initiator_comment
		$this->initiator_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_initiator_comment");
		$this->initiator_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_initiator_comment");

		// report_by
		$this->report_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_report_by");
		$this->report_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_report_by");

		// datetime_resolved
		$this->datetime_resolved->AdvancedSearch->SearchValue = $objForm->GetValue("x_datetime_resolved");
		$this->datetime_resolved->AdvancedSearch->SearchOperator = $objForm->GetValue("z_datetime_resolved");

		// resolved_action
		$this->resolved_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_resolved_action");
		$this->resolved_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_resolved_action");

		// resolved_comment
		$this->resolved_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_resolved_comment");
		$this->resolved_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_resolved_comment");

		// resolved_by
		$this->resolved_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_resolved_by");
		$this->resolved_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_resolved_by");

		// datetime_approved
		$this->datetime_approved->AdvancedSearch->SearchValue = $objForm->GetValue("x_datetime_approved");
		$this->datetime_approved->AdvancedSearch->SearchOperator = $objForm->GetValue("z_datetime_approved");

		// approval_action
		$this->approval_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_approval_action");
		$this->approval_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approval_action");

		// approval_comment
		$this->approval_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_approval_comment");
		$this->approval_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approval_comment");

		// approved_by
		$this->approved_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_approved_by");
		$this->approved_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approved_by");

		// closure_action
		$this->closure_action->AdvancedSearch->SearchValue = $objForm->GetValue("x_closure_action");
		$this->closure_action->AdvancedSearch->SearchOperator = $objForm->GetValue("z_closure_action");

		// closure_comment
		$this->closure_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_closure_comment");
		$this->closure_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_closure_comment");

		// last_updated_date
		$this->last_updated_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_last_updated_date");
		$this->last_updated_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_last_updated_date");

		// last_updated_by
		$this->last_updated_by->AdvancedSearch->SearchValue = $objForm->GetValue("x_last_updated_by");
		$this->last_updated_by->AdvancedSearch->SearchOperator = $objForm->GetValue("z_last_updated_by");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->amount_paid->FormValue == $this->amount_paid->CurrentValue && is_numeric(ew_StrToFloat($this->amount_paid->CurrentValue)))
			$this->amount_paid->CurrentValue = ew_StrToFloat($this->amount_paid->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// datetime_initiated
		// incident_id
		// staffid
		// staff_id
		// department
		// branch
		// departments
		// category
		// sub_category
		// start_date
		// end_date
		// duration
		// amount_paid
		// no_of_people_involved
		// incident_type
		// incident-category
		// incident_location
		// incident_description
		// upload
		// status
		// initiator_action
		// initiator_comment
		// report_by
		// datetime_resolved
		// resolved_action
		// resolved_comment
		// resolved_by
		// datetime_approved
		// approval_action
		// approval_comment
		// approved_by
		// closure_action
		// closure_comment
		// last_updated_date
		// last_updated_by

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime_initiated
		$this->datetime_initiated->ViewValue = $this->datetime_initiated->CurrentValue;
		$this->datetime_initiated->ViewValue = ew_FormatDateTime($this->datetime_initiated->ViewValue, 11);
		$this->datetime_initiated->ViewCustomAttributes = "";

		// incident_id
		$this->incident_id->ViewValue = $this->incident_id->CurrentValue;
		$this->incident_id->ViewCustomAttributes = "";

		// staffid
		$this->staffid->ViewValue = $this->staffid->CurrentValue;
		if (strval($this->staffid->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staffid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staffid->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->staffid->ViewValue = $this->staffid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staffid->ViewValue = $this->staffid->CurrentValue;
			}
		} else {
			$this->staffid->ViewValue = NULL;
		}
		$this->staffid->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->staff_id->ViewValue = $this->staff_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
			}
		} else {
			$this->staff_id->ViewValue = NULL;
		}
		$this->staff_id->ViewCustomAttributes = "";

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `department_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->department->ViewValue = $this->department->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->department->ViewValue = $this->department->CurrentValue;
			}
		} else {
			$this->department->ViewValue = NULL;
		}
		$this->department->ViewCustomAttributes = "";

		// branch
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `branch_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->branch->ViewValue = $this->branch->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->branch->ViewValue = $this->branch->CurrentValue;
			}
		} else {
			$this->branch->ViewValue = NULL;
		}
		$this->branch->ViewCustomAttributes = "";

		// departments
		if (strval($this->departments->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->departments->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
		$sWhereWrk = "";
		$this->departments->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->departments->ViewValue = $this->departments->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->departments->ViewValue = $this->departments->CurrentValue;
			}
		} else {
			$this->departments->ViewValue = NULL;
		}
		$this->departments->ViewCustomAttributes = "";

		// category
		if (strval($this->category->CurrentValue) <> "") {
			$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `category_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
		$sWhereWrk = "";
		$this->category->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->category->ViewValue = $this->category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->category->ViewValue = $this->category->CurrentValue;
			}
		} else {
			$this->category->ViewValue = NULL;
		}
		$this->category->ViewCustomAttributes = "";

		// sub_category
		if (strval($this->sub_category->CurrentValue) <> "") {
			$sFilterWrk = "`sub-category_id`" . ew_SearchString("=", $this->sub_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sub-category_id`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub-category`";
		$sWhereWrk = "";
		$this->sub_category->LookupFilters = array("dx1" => '`sub-category_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_category->ViewValue = $this->sub_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_category->ViewValue = $this->sub_category->CurrentValue;
			}
		} else {
			$this->sub_category->ViewValue = NULL;
		}
		$this->sub_category->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 2);
		$this->start_date->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 2);
		$this->end_date->ViewCustomAttributes = "";

		// duration
		$this->duration->ViewValue = $this->duration->CurrentValue;
		$this->duration->ViewCustomAttributes = "";

		// amount_paid
		$this->amount_paid->ViewValue = $this->amount_paid->CurrentValue;
		$this->amount_paid->ViewValue = ew_FormatCurrency($this->amount_paid->ViewValue, 2, -2, -2, -2);
		$this->amount_paid->ViewCustomAttributes = "";

		// no_of_people_involved
		$this->no_of_people_involved->ViewValue = $this->no_of_people_involved->CurrentValue;
		$this->no_of_people_involved->ViewCustomAttributes = "";

		// incident_type
		if (strval($this->incident_type->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `type_of_incident`";
		$sWhereWrk = "";
		$this->incident_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_type->ViewValue = $this->incident_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_type->ViewValue = $this->incident_type->CurrentValue;
			}
		} else {
			$this->incident_type->ViewValue = NULL;
		}
		$this->incident_type->ViewCustomAttributes = "";

		// incident-category
		if (strval($this->incident_category->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->incident_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->incident_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_category->ViewValue = $this->incident_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_category->ViewValue = $this->incident_category->CurrentValue;
			}
		} else {
			$this->incident_category->ViewValue = NULL;
		}
		$this->incident_category->ViewCustomAttributes = "";

		// incident_location
		if (strval($this->incident_location->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->incident_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_location->ViewValue = $this->incident_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_location->ViewValue = $this->incident_location->CurrentValue;
			}
		} else {
			$this->incident_location->ViewValue = NULL;
		}
		$this->incident_location->ViewCustomAttributes = "";

		// incident_description
		$this->incident_description->ViewValue = $this->incident_description->CurrentValue;
		$this->incident_description->ViewCustomAttributes = "";

		// upload
		$this->_upload->UploadPath = "picture/";
		if (!ew_Empty($this->_upload->Upload->DbValue)) {
			$this->_upload->ImageAlt = $this->_upload->FldAlt();
			$this->_upload->ViewValue = $this->_upload->Upload->DbValue;
		} else {
			$this->_upload->ViewValue = "";
		}
		$this->_upload->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
		$sWhereWrk = "";
		$this->status->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `description` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->status->ViewValue = $this->status->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->status->ViewValue = $this->status->CurrentValue;
			}
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

		// initiator_action
		if (strval($this->initiator_action->CurrentValue) <> "") {
			$this->initiator_action->ViewValue = $this->initiator_action->OptionCaption($this->initiator_action->CurrentValue);
		} else {
			$this->initiator_action->ViewValue = NULL;
		}
		$this->initiator_action->ViewCustomAttributes = "";

		// initiator_comment
		$this->initiator_comment->ViewValue = $this->initiator_comment->CurrentValue;
		$this->initiator_comment->ViewCustomAttributes = "";

		// report_by
		$this->report_by->ViewValue = $this->report_by->CurrentValue;
		if (strval($this->report_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->report_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->report_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->report_by->ViewValue = $this->report_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->report_by->ViewValue = $this->report_by->CurrentValue;
			}
		} else {
			$this->report_by->ViewValue = NULL;
		}
		$this->report_by->ViewCustomAttributes = "";

		// datetime_resolved
		$this->datetime_resolved->ViewValue = $this->datetime_resolved->CurrentValue;
		$this->datetime_resolved->ViewValue = ew_FormatDateTime($this->datetime_resolved->ViewValue, 11);
		$this->datetime_resolved->ViewCustomAttributes = "";

		// resolved_action
		if (strval($this->resolved_action->CurrentValue) <> "") {
			$this->resolved_action->ViewValue = $this->resolved_action->OptionCaption($this->resolved_action->CurrentValue);
		} else {
			$this->resolved_action->ViewValue = NULL;
		}
		$this->resolved_action->ViewCustomAttributes = "";

		// resolved_comment
		$this->resolved_comment->ViewValue = $this->resolved_comment->CurrentValue;
		$this->resolved_comment->ViewCustomAttributes = "";

		// resolved_by
		$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
		if (strval($this->resolved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->resolved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->resolved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->resolved_by->ViewValue = $this->resolved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
			}
		} else {
			$this->resolved_by->ViewValue = NULL;
		}
		$this->resolved_by->ViewCustomAttributes = "";

		// datetime_approved
		$this->datetime_approved->ViewValue = $this->datetime_approved->CurrentValue;
		$this->datetime_approved->ViewValue = ew_FormatDateTime($this->datetime_approved->ViewValue, 11);
		$this->datetime_approved->ViewCustomAttributes = "";

		// approval_action
		if (strval($this->approval_action->CurrentValue) <> "") {
			$this->approval_action->ViewValue = $this->approval_action->OptionCaption($this->approval_action->CurrentValue);
		} else {
			$this->approval_action->ViewValue = NULL;
		}
		$this->approval_action->ViewCustomAttributes = "";

		// approval_comment
		$this->approval_comment->ViewValue = $this->approval_comment->CurrentValue;
		$this->approval_comment->ViewCustomAttributes = "";

		// approved_by
		$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
		if (strval($this->approved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->approved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->approved_by->ViewValue = $this->approved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
			}
		} else {
			$this->approved_by->ViewValue = NULL;
		}
		$this->approved_by->ViewCustomAttributes = "";

		// closure_action
		if (strval($this->closure_action->CurrentValue) <> "") {
			$this->closure_action->ViewValue = $this->closure_action->OptionCaption($this->closure_action->CurrentValue);
		} else {
			$this->closure_action->ViewValue = NULL;
		}
		$this->closure_action->ViewCustomAttributes = "";

		// closure_comment
		$this->closure_comment->ViewValue = $this->closure_comment->CurrentValue;
		$this->closure_comment->ViewCustomAttributes = "";

		// last_updated_date
		$this->last_updated_date->ViewValue = $this->last_updated_date->CurrentValue;
		$this->last_updated_date->ViewValue = ew_FormatDateTime($this->last_updated_date->ViewValue, 17);
		$this->last_updated_date->ViewCustomAttributes = "";

		// last_updated_by
		$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
		if (strval($this->last_updated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->last_updated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->last_updated_by->ViewValue = $this->last_updated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
			}
		} else {
			$this->last_updated_by->ViewValue = NULL;
		}
		$this->last_updated_by->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// datetime_initiated
			$this->datetime_initiated->LinkCustomAttributes = "";
			$this->datetime_initiated->HrefValue = "";
			$this->datetime_initiated->TooltipValue = "";

			// incident_id
			$this->incident_id->LinkCustomAttributes = "";
			$this->incident_id->HrefValue = "";
			$this->incident_id->TooltipValue = "";

			// staffid
			$this->staffid->LinkCustomAttributes = "";
			$this->staffid->HrefValue = "";
			$this->staffid->TooltipValue = "";

			// staff_id
			$this->staff_id->LinkCustomAttributes = "";
			$this->staff_id->HrefValue = "";
			$this->staff_id->TooltipValue = "";

			// department
			$this->department->LinkCustomAttributes = "";
			$this->department->HrefValue = "";
			$this->department->TooltipValue = "";

			// branch
			$this->branch->LinkCustomAttributes = "";
			$this->branch->HrefValue = "";
			$this->branch->TooltipValue = "";

			// departments
			$this->departments->LinkCustomAttributes = "";
			$this->departments->HrefValue = "";
			$this->departments->TooltipValue = "";

			// category
			$this->category->LinkCustomAttributes = "";
			$this->category->HrefValue = "";
			$this->category->TooltipValue = "";

			// sub_category
			$this->sub_category->LinkCustomAttributes = "";
			$this->sub_category->HrefValue = "";
			$this->sub_category->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// end_date
			$this->end_date->LinkCustomAttributes = "";
			$this->end_date->HrefValue = "";
			$this->end_date->TooltipValue = "";

			// duration
			$this->duration->LinkCustomAttributes = "";
			$this->duration->HrefValue = "";
			$this->duration->TooltipValue = "";

			// amount_paid
			$this->amount_paid->LinkCustomAttributes = "";
			$this->amount_paid->HrefValue = "";
			$this->amount_paid->TooltipValue = "";

			// no_of_people_involved
			$this->no_of_people_involved->LinkCustomAttributes = "";
			$this->no_of_people_involved->HrefValue = "";
			$this->no_of_people_involved->TooltipValue = "";

			// incident_type
			$this->incident_type->LinkCustomAttributes = "";
			$this->incident_type->HrefValue = "";
			$this->incident_type->TooltipValue = "";

			// incident-category
			$this->incident_category->LinkCustomAttributes = "";
			$this->incident_category->HrefValue = "";
			$this->incident_category->TooltipValue = "";

			// incident_location
			$this->incident_location->LinkCustomAttributes = "";
			$this->incident_location->HrefValue = "";
			$this->incident_location->TooltipValue = "";

			// incident_description
			$this->incident_description->LinkCustomAttributes = "";
			$this->incident_description->HrefValue = "";
			$this->incident_description->TooltipValue = "";

			// upload
			$this->_upload->LinkCustomAttributes = "";
			$this->_upload->UploadPath = "picture/";
			if (!ew_Empty($this->_upload->Upload->DbValue)) {
				$this->_upload->HrefValue = "%u"; // Add prefix/suffix
				$this->_upload->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->_upload->HrefValue = ew_FullUrl($this->_upload->HrefValue, "href");
			} else {
				$this->_upload->HrefValue = "";
			}
			$this->_upload->HrefValue2 = $this->_upload->UploadPath . $this->_upload->Upload->DbValue;
			$this->_upload->TooltipValue = "";
			if ($this->_upload->UseColorbox) {
				if (ew_Empty($this->_upload->TooltipValue))
					$this->_upload->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->_upload->LinkAttrs["data-rel"] = "report_form_x__upload";
				ew_AppendClass($this->_upload->LinkAttrs["class"], "ewLightbox");
			}

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// initiator_action
			$this->initiator_action->LinkCustomAttributes = "";
			$this->initiator_action->HrefValue = "";
			$this->initiator_action->TooltipValue = "";

			// initiator_comment
			$this->initiator_comment->LinkCustomAttributes = "";
			$this->initiator_comment->HrefValue = "";
			$this->initiator_comment->TooltipValue = "";

			// report_by
			$this->report_by->LinkCustomAttributes = "";
			$this->report_by->HrefValue = "";
			$this->report_by->TooltipValue = "";

			// datetime_resolved
			$this->datetime_resolved->LinkCustomAttributes = "";
			$this->datetime_resolved->HrefValue = "";
			$this->datetime_resolved->TooltipValue = "";

			// resolved_action
			$this->resolved_action->LinkCustomAttributes = "";
			$this->resolved_action->HrefValue = "";
			$this->resolved_action->TooltipValue = "";

			// resolved_comment
			$this->resolved_comment->LinkCustomAttributes = "";
			$this->resolved_comment->HrefValue = "";
			$this->resolved_comment->TooltipValue = "";

			// resolved_by
			$this->resolved_by->LinkCustomAttributes = "";
			$this->resolved_by->HrefValue = "";
			$this->resolved_by->TooltipValue = "";

			// datetime_approved
			$this->datetime_approved->LinkCustomAttributes = "";
			$this->datetime_approved->HrefValue = "";
			$this->datetime_approved->TooltipValue = "";

			// approval_action
			$this->approval_action->LinkCustomAttributes = "";
			$this->approval_action->HrefValue = "";
			$this->approval_action->TooltipValue = "";

			// approval_comment
			$this->approval_comment->LinkCustomAttributes = "";
			$this->approval_comment->HrefValue = "";
			$this->approval_comment->TooltipValue = "";

			// approved_by
			$this->approved_by->LinkCustomAttributes = "";
			$this->approved_by->HrefValue = "";
			$this->approved_by->TooltipValue = "";

			// closure_action
			$this->closure_action->LinkCustomAttributes = "";
			$this->closure_action->HrefValue = "";
			$this->closure_action->TooltipValue = "";

			// closure_comment
			$this->closure_comment->LinkCustomAttributes = "";
			$this->closure_comment->HrefValue = "";
			$this->closure_comment->TooltipValue = "";

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";
			$this->last_updated_date->TooltipValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";
			$this->last_updated_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// datetime_initiated
			$this->datetime_initiated->EditAttrs["class"] = "form-control";
			$this->datetime_initiated->EditCustomAttributes = "";
			$this->datetime_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime_initiated->AdvancedSearch->SearchValue, 11), 11));
			$this->datetime_initiated->PlaceHolder = ew_RemoveHtml($this->datetime_initiated->FldCaption());

			// incident_id
			$this->incident_id->EditAttrs["class"] = "form-control";
			$this->incident_id->EditCustomAttributes = "";
			$this->incident_id->EditValue = ew_HtmlEncode($this->incident_id->AdvancedSearch->SearchValue);
			$this->incident_id->PlaceHolder = ew_RemoveHtml($this->incident_id->FldCaption());

			// staffid
			$this->staffid->EditAttrs["class"] = "form-control";
			$this->staffid->EditCustomAttributes = "";
			$this->staffid->EditValue = ew_HtmlEncode($this->staffid->AdvancedSearch->SearchValue);
			if (strval($this->staffid->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staffid->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->staffid->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->staffid->EditValue = $this->staffid->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staffid->EditValue = ew_HtmlEncode($this->staffid->AdvancedSearch->SearchValue);
				}
			} else {
				$this->staffid->EditValue = NULL;
			}
			$this->staffid->PlaceHolder = ew_RemoveHtml($this->staffid->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";
			$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->AdvancedSearch->SearchValue);
			if (strval($this->staff_id->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->staff_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->staff_id->EditValue = $this->staff_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->staff_id->EditValue = ew_HtmlEncode($this->staff_id->AdvancedSearch->SearchValue);
				}
			} else {
				$this->staff_id->EditValue = NULL;
			}
			$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

			// department
			$this->department->EditCustomAttributes = "";
			if (trim(strval($this->department->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `depertment`";
			$sWhereWrk = "";
			$this->department->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `department_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->department->AdvancedSearch->ViewValue = $this->department->DisplayValue($arwrk);
			} else {
				$this->department->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->department->EditValue = $arwrk;

			// branch
			$this->branch->EditCustomAttributes = "";
			if (trim(strval($this->branch->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `branch`";
			$sWhereWrk = "";
			$this->branch->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `branch_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->branch->AdvancedSearch->ViewValue = $this->branch->DisplayValue($arwrk);
			} else {
				$this->branch->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->branch->EditValue = $arwrk;

			// departments
			$this->departments->EditCustomAttributes = "";
			if (trim(strval($this->departments->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->departments->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departments`";
			$sWhereWrk = "";
			$this->departments->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->departments->AdvancedSearch->ViewValue = $this->departments->DisplayValue($arwrk);
			} else {
				$this->departments->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->departments->EditValue = $arwrk;

			// category
			$this->category->EditCustomAttributes = "";
			if (trim(strval($this->category->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `category_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `code_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `category`";
			$sWhereWrk = "";
			$this->category->LookupFilters = array("dx1" => '`description`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->category->AdvancedSearch->ViewValue = $this->category->DisplayValue($arwrk);
			} else {
				$this->category->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->category->EditValue = $arwrk;

			// sub_category
			$this->sub_category->EditCustomAttributes = "";
			if (trim(strval($this->sub_category->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`sub-category_id`" . ew_SearchString("=", $this->sub_category->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `sub-category_id`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `category_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sub-category`";
			$sWhereWrk = "";
			$this->sub_category->LookupFilters = array("dx1" => '`sub-category_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->sub_category->AdvancedSearch->ViewValue = $this->sub_category->DisplayValue($arwrk);
			} else {
				$this->sub_category->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->sub_category->EditValue = $arwrk;

			// start_date
			$this->start_date->EditAttrs["class"] = "form-control";
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->start_date->AdvancedSearch->SearchValue, 2), 2));
			$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

			// end_date
			$this->end_date->EditAttrs["class"] = "form-control";
			$this->end_date->EditCustomAttributes = "";
			$this->end_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->end_date->AdvancedSearch->SearchValue, 2), 2));
			$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

			// duration
			$this->duration->EditAttrs["class"] = "form-control";
			$this->duration->EditCustomAttributes = "";
			$this->duration->EditValue = ew_HtmlEncode($this->duration->AdvancedSearch->SearchValue);
			$this->duration->PlaceHolder = ew_RemoveHtml($this->duration->FldCaption());

			// amount_paid
			$this->amount_paid->EditAttrs["class"] = "form-control";
			$this->amount_paid->EditCustomAttributes = "";
			$this->amount_paid->EditValue = ew_HtmlEncode($this->amount_paid->AdvancedSearch->SearchValue);
			$this->amount_paid->PlaceHolder = ew_RemoveHtml($this->amount_paid->FldCaption());

			// no_of_people_involved
			$this->no_of_people_involved->EditAttrs["class"] = "form-control";
			$this->no_of_people_involved->EditCustomAttributes = "";
			$this->no_of_people_involved->EditValue = ew_HtmlEncode($this->no_of_people_involved->AdvancedSearch->SearchValue);
			$this->no_of_people_involved->PlaceHolder = ew_RemoveHtml($this->no_of_people_involved->FldCaption());

			// incident_type
			$this->incident_type->EditCustomAttributes = "";
			if (trim(strval($this->incident_type->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_type->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `type_of_incident`";
			$sWhereWrk = "";
			$this->incident_type->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_type->AdvancedSearch->ViewValue = $this->incident_type->DisplayValue($arwrk);
			} else {
				$this->incident_type->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_type->EditValue = $arwrk;

			// incident-category
			$this->incident_category->EditCustomAttributes = "";
			if (trim(strval($this->incident_category->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->incident_category->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident-category`";
			$sWhereWrk = "";
			$this->incident_category->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_category->AdvancedSearch->ViewValue = $this->incident_category->DisplayValue($arwrk);
			} else {
				$this->incident_category->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_category->EditValue = $arwrk;

			// incident_location
			$this->incident_location->EditCustomAttributes = "";
			if (trim(strval($this->incident_location->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_location->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `incident_location`";
			$sWhereWrk = "";
			$this->incident_location->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->incident_location->AdvancedSearch->ViewValue = $this->incident_location->DisplayValue($arwrk);
			} else {
				$this->incident_location->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->incident_location->EditValue = $arwrk;

			// incident_description
			$this->incident_description->EditAttrs["class"] = "form-control";
			$this->incident_description->EditCustomAttributes = "";
			$this->incident_description->EditValue = ew_HtmlEncode($this->incident_description->AdvancedSearch->SearchValue);
			$this->incident_description->PlaceHolder = ew_RemoveHtml($this->incident_description->FldCaption());

			// upload
			$this->_upload->EditAttrs["class"] = "form-control";
			$this->_upload->EditCustomAttributes = "";
			$this->_upload->EditValue = ew_HtmlEncode($this->_upload->AdvancedSearch->SearchValue);
			$this->_upload->PlaceHolder = ew_RemoveHtml($this->_upload->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `status`";
			$sWhereWrk = "";
			$this->status->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `description` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->status->EditValue = $arwrk;

			// initiator_action
			$this->initiator_action->EditCustomAttributes = "";
			$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

			// initiator_comment
			$this->initiator_comment->EditAttrs["class"] = "form-control";
			$this->initiator_comment->EditCustomAttributes = "";
			$this->initiator_comment->EditValue = ew_HtmlEncode($this->initiator_comment->AdvancedSearch->SearchValue);
			$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

			// report_by
			$this->report_by->EditAttrs["class"] = "form-control";
			$this->report_by->EditCustomAttributes = "";
			$this->report_by->EditValue = ew_HtmlEncode($this->report_by->AdvancedSearch->SearchValue);
			if (strval($this->report_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->report_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->report_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->report_by->EditValue = $this->report_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->report_by->EditValue = ew_HtmlEncode($this->report_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->report_by->EditValue = NULL;
			}
			$this->report_by->PlaceHolder = ew_RemoveHtml($this->report_by->FldCaption());

			// datetime_resolved
			$this->datetime_resolved->EditAttrs["class"] = "form-control";
			$this->datetime_resolved->EditCustomAttributes = "";
			$this->datetime_resolved->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime_resolved->AdvancedSearch->SearchValue, 11), 11));
			$this->datetime_resolved->PlaceHolder = ew_RemoveHtml($this->datetime_resolved->FldCaption());

			// resolved_action
			$this->resolved_action->EditCustomAttributes = "";
			$this->resolved_action->EditValue = $this->resolved_action->Options(FALSE);

			// resolved_comment
			$this->resolved_comment->EditAttrs["class"] = "form-control";
			$this->resolved_comment->EditCustomAttributes = "";
			$this->resolved_comment->EditValue = ew_HtmlEncode($this->resolved_comment->AdvancedSearch->SearchValue);
			$this->resolved_comment->PlaceHolder = ew_RemoveHtml($this->resolved_comment->FldCaption());

			// resolved_by
			$this->resolved_by->EditAttrs["class"] = "form-control";
			$this->resolved_by->EditCustomAttributes = "";
			$this->resolved_by->EditValue = ew_HtmlEncode($this->resolved_by->AdvancedSearch->SearchValue);
			if (strval($this->resolved_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->resolved_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->resolved_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->resolved_by->EditValue = $this->resolved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->resolved_by->EditValue = ew_HtmlEncode($this->resolved_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->resolved_by->EditValue = NULL;
			}
			$this->resolved_by->PlaceHolder = ew_RemoveHtml($this->resolved_by->FldCaption());

			// datetime_approved
			$this->datetime_approved->EditAttrs["class"] = "form-control";
			$this->datetime_approved->EditCustomAttributes = "";
			$this->datetime_approved->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime_approved->AdvancedSearch->SearchValue, 11), 11));
			$this->datetime_approved->PlaceHolder = ew_RemoveHtml($this->datetime_approved->FldCaption());

			// approval_action
			$this->approval_action->EditCustomAttributes = "";
			$this->approval_action->EditValue = $this->approval_action->Options(FALSE);

			// approval_comment
			$this->approval_comment->EditAttrs["class"] = "form-control";
			$this->approval_comment->EditCustomAttributes = "";
			$this->approval_comment->EditValue = ew_HtmlEncode($this->approval_comment->AdvancedSearch->SearchValue);
			$this->approval_comment->PlaceHolder = ew_RemoveHtml($this->approval_comment->FldCaption());

			// approved_by
			$this->approved_by->EditAttrs["class"] = "form-control";
			$this->approved_by->EditCustomAttributes = "";
			$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->AdvancedSearch->SearchValue);
			if (strval($this->approved_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->approved_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->approved_by->EditValue = $this->approved_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->approved_by->EditValue = ew_HtmlEncode($this->approved_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->approved_by->EditValue = NULL;
			}
			$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

			// closure_action
			$this->closure_action->EditCustomAttributes = "";
			$this->closure_action->EditValue = $this->closure_action->Options(FALSE);

			// closure_comment
			$this->closure_comment->EditAttrs["class"] = "form-control";
			$this->closure_comment->EditCustomAttributes = "";
			$this->closure_comment->EditValue = ew_HtmlEncode($this->closure_comment->AdvancedSearch->SearchValue);
			$this->closure_comment->PlaceHolder = ew_RemoveHtml($this->closure_comment->FldCaption());

			// last_updated_date
			$this->last_updated_date->EditAttrs["class"] = "form-control";
			$this->last_updated_date->EditCustomAttributes = "";
			$this->last_updated_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->last_updated_date->AdvancedSearch->SearchValue, 17), 17));
			$this->last_updated_date->PlaceHolder = ew_RemoveHtml($this->last_updated_date->FldCaption());

			// last_updated_by
			$this->last_updated_by->EditAttrs["class"] = "form-control";
			$this->last_updated_by->EditCustomAttributes = "";
			$this->last_updated_by->EditValue = ew_HtmlEncode($this->last_updated_by->AdvancedSearch->SearchValue);
			if (strval($this->last_updated_by->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->last_updated_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->last_updated_by->EditValue = $this->last_updated_by->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->last_updated_by->EditValue = ew_HtmlEncode($this->last_updated_by->AdvancedSearch->SearchValue);
				}
			} else {
				$this->last_updated_by->EditValue = NULL;
			}
			$this->last_updated_by->PlaceHolder = ew_RemoveHtml($this->last_updated_by->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->datetime_initiated->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->datetime_initiated->FldErrMsg());
		}
		if (!ew_CheckInteger($this->staff_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->staff_id->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->start_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->start_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->end_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->end_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->duration->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->duration->FldErrMsg());
		}
		if (!ew_CheckNumber($this->amount_paid->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->amount_paid->FldErrMsg());
		}
		if (!ew_CheckInteger($this->report_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->report_by->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->datetime_resolved->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->datetime_resolved->FldErrMsg());
		}
		if (!ew_CheckInteger($this->resolved_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->resolved_by->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->datetime_approved->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->datetime_approved->FldErrMsg());
		}
		if (!ew_CheckInteger($this->approved_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->approved_by->FldErrMsg());
		}
		if (!ew_CheckShortEuroDate($this->last_updated_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->last_updated_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->last_updated_by->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->last_updated_by->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id->AdvancedSearch->Load();
		$this->datetime_initiated->AdvancedSearch->Load();
		$this->incident_id->AdvancedSearch->Load();
		$this->staffid->AdvancedSearch->Load();
		$this->staff_id->AdvancedSearch->Load();
		$this->department->AdvancedSearch->Load();
		$this->branch->AdvancedSearch->Load();
		$this->departments->AdvancedSearch->Load();
		$this->category->AdvancedSearch->Load();
		$this->sub_category->AdvancedSearch->Load();
		$this->start_date->AdvancedSearch->Load();
		$this->end_date->AdvancedSearch->Load();
		$this->duration->AdvancedSearch->Load();
		$this->amount_paid->AdvancedSearch->Load();
		$this->no_of_people_involved->AdvancedSearch->Load();
		$this->incident_type->AdvancedSearch->Load();
		$this->incident_category->AdvancedSearch->Load();
		$this->incident_location->AdvancedSearch->Load();
		$this->incident_description->AdvancedSearch->Load();
		$this->_upload->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->initiator_action->AdvancedSearch->Load();
		$this->initiator_comment->AdvancedSearch->Load();
		$this->report_by->AdvancedSearch->Load();
		$this->datetime_resolved->AdvancedSearch->Load();
		$this->resolved_action->AdvancedSearch->Load();
		$this->resolved_comment->AdvancedSearch->Load();
		$this->resolved_by->AdvancedSearch->Load();
		$this->datetime_approved->AdvancedSearch->Load();
		$this->approval_action->AdvancedSearch->Load();
		$this->approval_comment->AdvancedSearch->Load();
		$this->approved_by->AdvancedSearch->Load();
		$this->closure_action->AdvancedSearch->Load();
		$this->closure_comment->AdvancedSearch->Load();
		$this->last_updated_date->AdvancedSearch->Load();
		$this->last_updated_by->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("report_formlist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_staffid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_department":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `department_id` AS `LinkFld`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`department_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `department_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_branch":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `branch_id` AS `LinkFld`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`branch_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `branch_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_departments":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "n" => 5);
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->departments, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `category_id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`description`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`category_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "n" => 3, "f1" => '`code_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code_id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_sub_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `sub-category_id` AS `LinkFld`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub-category`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`sub-category_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`sub-category_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "n" => 3, "f1" => '`category_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_type":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `type_of_incident`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_category":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `id` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_incident_location":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `code` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_status":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `code` AS `LinkFld`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`code` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `description` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_report_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_resolved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_last_updated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_staffid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld` FROM `users`";
			$sWhereWrk = "`staffno` LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_staff_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->staff_id) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_report_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->report_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_resolved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->resolved_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approved_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->approved_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_last_updated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld` FROM `users`";
			$sWhereWrk = "`firstname` LIKE '{query_value}%' OR CONCAT(COALESCE(`firstname`, ''),'" . ew_ValueSeparator(1, $this->last_updated_by) . "',COALESCE(`lastname`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($report_form_search)) $report_form_search = new creport_form_search();

// Page init
$report_form_search->Page_Init();

// Page main
$report_form_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$report_form_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($report_form_search->IsModal) { ?>
var CurrentAdvancedSearchForm = freport_formsearch = new ew_Form("freport_formsearch", "search");
<?php } else { ?>
var CurrentForm = freport_formsearch = new ew_Form("freport_formsearch", "search");
<?php } ?>

// Form_CustomValidate event
freport_formsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
freport_formsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
freport_formsearch.Lists["x_staffid"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formsearch.Lists["x_staffid"].Data = "<?php echo $report_form_search->staffid->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.AutoSuggests["x_staffid"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_search->staffid->LookupFilterQuery(TRUE, "search"))) ?>;
freport_formsearch.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formsearch.Lists["x_staff_id"].Data = "<?php echo $report_form_search->staff_id->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.AutoSuggests["x_staff_id"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_search->staff_id->LookupFilterQuery(TRUE, "search"))) ?>;
freport_formsearch.Lists["x_department"] = {"LinkField":"x_department_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_department_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"depertment"};
freport_formsearch.Lists["x_department"].Data = "<?php echo $report_form_search->department->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
freport_formsearch.Lists["x_branch"].Data = "<?php echo $report_form_search->branch->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_departments"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departments"};
freport_formsearch.Lists["x_departments"].Data = "<?php echo $report_form_search->departments->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_category"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":["x_departments"],"ChildFields":["x_sub_category"],"FilterFields":["x_code_id"],"Options":[],"Template":"","LinkTable":"category"};
freport_formsearch.Lists["x_category"].Data = "<?php echo $report_form_search->category->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_sub_category"] = {"LinkField":"x_sub_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sub_category_name","","",""],"ParentFields":["x_category"],"ChildFields":[],"FilterFields":["x_category_id"],"Options":[],"Template":"","LinkTable":"sub_category"};
freport_formsearch.Lists["x_sub_category"].Data = "<?php echo $report_form_search->sub_category->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_incident_type"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"type_of_incident"};
freport_formsearch.Lists["x_incident_type"].Data = "<?php echo $report_form_search->incident_type->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_incident_category"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_category"};
freport_formsearch.Lists["x_incident_category"].Data = "<?php echo $report_form_search->incident_category->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_incident_location"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"incident_location"};
freport_formsearch.Lists["x_incident_location"].Data = "<?php echo $report_form_search->incident_location->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
freport_formsearch.Lists["x_status"].Data = "<?php echo $report_form_search->status->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.Lists["x_initiator_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formsearch.Lists["x_initiator_action"].Options = <?php echo json_encode($report_form_search->initiator_action->Options()) ?>;
freport_formsearch.Lists["x_report_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formsearch.Lists["x_report_by"].Data = "<?php echo $report_form_search->report_by->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.AutoSuggests["x_report_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_search->report_by->LookupFilterQuery(TRUE, "search"))) ?>;
freport_formsearch.Lists["x_resolved_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formsearch.Lists["x_resolved_action"].Options = <?php echo json_encode($report_form_search->resolved_action->Options()) ?>;
freport_formsearch.Lists["x_resolved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formsearch.Lists["x_resolved_by"].Data = "<?php echo $report_form_search->resolved_by->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.AutoSuggests["x_resolved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_search->resolved_by->LookupFilterQuery(TRUE, "search"))) ?>;
freport_formsearch.Lists["x_approval_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formsearch.Lists["x_approval_action"].Options = <?php echo json_encode($report_form_search->approval_action->Options()) ?>;
freport_formsearch.Lists["x_approved_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formsearch.Lists["x_approved_by"].Data = "<?php echo $report_form_search->approved_by->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.AutoSuggests["x_approved_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_search->approved_by->LookupFilterQuery(TRUE, "search"))) ?>;
freport_formsearch.Lists["x_closure_action"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
freport_formsearch.Lists["x_closure_action"].Options = <?php echo json_encode($report_form_search->closure_action->Options()) ?>;
freport_formsearch.Lists["x_last_updated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
freport_formsearch.Lists["x_last_updated_by"].Data = "<?php echo $report_form_search->last_updated_by->LookupFilterQuery(FALSE, "search") ?>";
freport_formsearch.AutoSuggests["x_last_updated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $report_form_search->last_updated_by->LookupFilterQuery(TRUE, "search"))) ?>;

// Form object for search
// Validate function for search

freport_formsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_datetime_initiated");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->datetime_initiated->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_staff_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->staff_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_start_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->start_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_end_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->end_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_duration");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->duration->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_amount_paid");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->amount_paid->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_report_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->report_by->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_datetime_resolved");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->datetime_resolved->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_resolved_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->resolved_by->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_datetime_approved");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->datetime_approved->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_approved_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->approved_by->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_last_updated_date");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->last_updated_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_last_updated_by");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($report_form->last_updated_by->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $report_form_search->ShowPageHeader(); ?>
<?php
$report_form_search->ShowMessage();
?>
<form name="freport_formsearch" id="freport_formsearch" class="<?php echo $report_form_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($report_form_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $report_form_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="report_form">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($report_form_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($report_form->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_id"><?php echo $report_form->id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->id->CellAttributes() ?>>
			<span id="el_report_form_id">
<input type="text" data-table="report_form" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($report_form->id->getPlaceHolder()) ?>" value="<?php echo $report_form->id->EditValue ?>"<?php echo $report_form->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->datetime_initiated->Visible) { // datetime_initiated ?>
	<div id="r_datetime_initiated" class="form-group">
		<label for="x_datetime_initiated" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_datetime_initiated"><?php echo $report_form->datetime_initiated->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_datetime_initiated" id="z_datetime_initiated" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->datetime_initiated->CellAttributes() ?>>
			<span id="el_report_form_datetime_initiated">
<input type="text" data-table="report_form" data-field="x_datetime_initiated" data-format="11" name="x_datetime_initiated" id="x_datetime_initiated" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->datetime_initiated->getPlaceHolder()) ?>" value="<?php echo $report_form->datetime_initiated->EditValue ?>"<?php echo $report_form->datetime_initiated->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_id->Visible) { // incident_id ?>
	<div id="r_incident_id" class="form-group">
		<label for="x_incident_id" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_incident_id"><?php echo $report_form->incident_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_incident_id" id="z_incident_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->incident_id->CellAttributes() ?>>
			<span id="el_report_form_incident_id">
<input type="text" data-table="report_form" data-field="x_incident_id" name="x_incident_id" id="x_incident_id" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->incident_id->getPlaceHolder()) ?>" value="<?php echo $report_form->incident_id->EditValue ?>"<?php echo $report_form->incident_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->staffid->Visible) { // staffid ?>
	<div id="r_staffid" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_staffid"><?php echo $report_form->staffid->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_staffid" id="z_staffid" value="LIKE"></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->staffid->CellAttributes() ?>>
			<span id="el_report_form_staffid">
<?php
$wrkonchange = trim(" " . @$report_form->staffid->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->staffid->EditAttrs["onchange"] = "";
?>
<span id="as_x_staffid" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_staffid" id="sv_x_staffid" value="<?php echo $report_form->staffid->EditValue ?>" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->staffid->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->staffid->getPlaceHolder()) ?>"<?php echo $report_form->staffid->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_staffid" data-value-separator="<?php echo $report_form->staffid->DisplayValueSeparatorAttribute() ?>" name="x_staffid" id="x_staffid" value="<?php echo ew_HtmlEncode($report_form->staffid->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formsearch.CreateAutoSuggest({"id":"x_staffid","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->staff_id->Visible) { // staff_id ?>
	<div id="r_staff_id" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_staff_id"><?php echo $report_form->staff_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_staff_id" id="z_staff_id" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->staff_id->CellAttributes() ?>>
			<span id="el_report_form_staff_id">
<?php
$wrkonchange = trim(" " . @$report_form->staff_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->staff_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_staff_id" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_staff_id" id="sv_x_staff_id" value="<?php echo $report_form->staff_id->EditValue ?>" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->staff_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->staff_id->getPlaceHolder()) ?>"<?php echo $report_form->staff_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_staff_id" data-value-separator="<?php echo $report_form->staff_id->DisplayValueSeparatorAttribute() ?>" name="x_staff_id" id="x_staff_id" value="<?php echo ew_HtmlEncode($report_form->staff_id->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formsearch.CreateAutoSuggest({"id":"x_staff_id","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->department->Visible) { // department ?>
	<div id="r_department" class="form-group">
		<label for="x_department" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_department"><?php echo $report_form->department->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_department" id="z_department" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->department->CellAttributes() ?>>
			<span id="el_report_form_department">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->department->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->department->AdvancedSearch->ViewValue ?>
	</span>
	<?php if (!$report_form->department->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_department" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->department->RadioButtonListHtml(TRUE, "x_department") ?>
		</div>
	</div>
	<div id="tp_x_department" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_department" data-value-separator="<?php echo $report_form->department->DisplayValueSeparatorAttribute() ?>" name="x_department" id="x_department" value="{value}"<?php echo $report_form->department->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->branch->Visible) { // branch ?>
	<div id="r_branch" class="form-group">
		<label for="x_branch" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_branch"><?php echo $report_form->branch->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_branch" id="z_branch" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->branch->CellAttributes() ?>>
			<span id="el_report_form_branch">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->branch->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->branch->AdvancedSearch->ViewValue ?>
	</span>
	<?php if (!$report_form->branch->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_branch" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->branch->RadioButtonListHtml(TRUE, "x_branch") ?>
		</div>
	</div>
	<div id="tp_x_branch" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_branch" data-value-separator="<?php echo $report_form->branch->DisplayValueSeparatorAttribute() ?>" name="x_branch" id="x_branch" value="{value}"<?php echo $report_form->branch->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->departments->Visible) { // departments ?>
	<div id="r_departments" class="form-group">
		<label for="x_departments" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_departments"><?php echo $report_form->departments->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_departments" id="z_departments" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->departments->CellAttributes() ?>>
			<span id="el_report_form_departments">
<?php $report_form->departments->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report_form->departments->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_departments"><?php echo (strval($report_form->departments->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report_form->departments->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report_form->departments->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_departments',m:0,n:5});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report_form->departments->ReadOnly || $report_form->departments->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report_form" data-field="x_departments" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report_form->departments->DisplayValueSeparatorAttribute() ?>" name="x_departments" id="x_departments" value="<?php echo $report_form->departments->AdvancedSearch->SearchValue ?>"<?php echo $report_form->departments->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->category->Visible) { // category ?>
	<div id="r_category" class="form-group">
		<label for="x_category" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_category"><?php echo $report_form->category->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_category" id="z_category" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->category->CellAttributes() ?>>
			<span id="el_report_form_category">
<?php $report_form->category->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$report_form->category->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_category"><?php echo (strval($report_form->category->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report_form->category->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report_form->category->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_category',m:0,n:3});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report_form->category->ReadOnly || $report_form->category->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report_form" data-field="x_category" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report_form->category->DisplayValueSeparatorAttribute() ?>" name="x_category" id="x_category" value="<?php echo $report_form->category->AdvancedSearch->SearchValue ?>"<?php echo $report_form->category->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->sub_category->Visible) { // sub_category ?>
	<div id="r_sub_category" class="form-group">
		<label for="x_sub_category" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_sub_category"><?php echo $report_form->sub_category->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_sub_category" id="z_sub_category" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->sub_category->CellAttributes() ?>>
			<span id="el_report_form_sub_category">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_sub_category"><?php echo (strval($report_form->sub_category->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $report_form->sub_category->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($report_form->sub_category->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_sub_category',m:0,n:3});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($report_form->sub_category->ReadOnly || $report_form->sub_category->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="report_form" data-field="x_sub_category" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $report_form->sub_category->DisplayValueSeparatorAttribute() ?>" name="x_sub_category" id="x_sub_category" value="<?php echo $report_form->sub_category->AdvancedSearch->SearchValue ?>"<?php echo $report_form->sub_category->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->start_date->Visible) { // start_date ?>
	<div id="r_start_date" class="form-group">
		<label for="x_start_date" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_start_date"><?php echo $report_form->start_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_start_date" id="z_start_date" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->start_date->CellAttributes() ?>>
			<span id="el_report_form_start_date">
<input type="text" data-table="report_form" data-field="x_start_date" data-format="2" name="x_start_date" id="x_start_date" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->start_date->getPlaceHolder()) ?>" value="<?php echo $report_form->start_date->EditValue ?>"<?php echo $report_form->start_date->EditAttributes() ?>>
<?php if (!$report_form->start_date->ReadOnly && !$report_form->start_date->Disabled && !isset($report_form->start_date->EditAttrs["readonly"]) && !isset($report_form->start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freport_formsearch", "x_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":2});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->end_date->Visible) { // end_date ?>
	<div id="r_end_date" class="form-group">
		<label for="x_end_date" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_end_date"><?php echo $report_form->end_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_end_date" id="z_end_date" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->end_date->CellAttributes() ?>>
			<span id="el_report_form_end_date">
<input type="text" data-table="report_form" data-field="x_end_date" data-format="2" name="x_end_date" id="x_end_date" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->end_date->getPlaceHolder()) ?>" value="<?php echo $report_form->end_date->EditValue ?>"<?php echo $report_form->end_date->EditAttributes() ?>>
<?php if (!$report_form->end_date->ReadOnly && !$report_form->end_date->Disabled && !isset($report_form->end_date->EditAttrs["readonly"]) && !isset($report_form->end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("freport_formsearch", "x_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":2});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->duration->Visible) { // duration ?>
	<div id="r_duration" class="form-group">
		<label for="x_duration" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_duration"><?php echo $report_form->duration->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_duration" id="z_duration" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->duration->CellAttributes() ?>>
			<span id="el_report_form_duration">
<input type="text" data-table="report_form" data-field="x_duration" name="x_duration" id="x_duration" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->duration->getPlaceHolder()) ?>" value="<?php echo $report_form->duration->EditValue ?>"<?php echo $report_form->duration->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->amount_paid->Visible) { // amount_paid ?>
	<div id="r_amount_paid" class="form-group">
		<label for="x_amount_paid" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_amount_paid"><?php echo $report_form->amount_paid->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_amount_paid" id="z_amount_paid" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->amount_paid->CellAttributes() ?>>
			<span id="el_report_form_amount_paid">
<input type="text" data-table="report_form" data-field="x_amount_paid" name="x_amount_paid" id="x_amount_paid" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->amount_paid->getPlaceHolder()) ?>" value="<?php echo $report_form->amount_paid->EditValue ?>"<?php echo $report_form->amount_paid->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->no_of_people_involved->Visible) { // no_of_people_involved ?>
	<div id="r_no_of_people_involved" class="form-group">
		<label for="x_no_of_people_involved" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_no_of_people_involved"><?php echo $report_form->no_of_people_involved->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_no_of_people_involved" id="z_no_of_people_involved" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->no_of_people_involved->CellAttributes() ?>>
			<span id="el_report_form_no_of_people_involved">
<input type="text" data-table="report_form" data-field="x_no_of_people_involved" name="x_no_of_people_involved" id="x_no_of_people_involved" size="18" placeholder="<?php echo ew_HtmlEncode($report_form->no_of_people_involved->getPlaceHolder()) ?>" value="<?php echo $report_form->no_of_people_involved->EditValue ?>"<?php echo $report_form->no_of_people_involved->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_type->Visible) { // incident_type ?>
	<div id="r_incident_type" class="form-group">
		<label for="x_incident_type" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_incident_type"><?php echo $report_form->incident_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_incident_type" id="z_incident_type" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->incident_type->CellAttributes() ?>>
			<span id="el_report_form_incident_type">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->incident_type->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->incident_type->AdvancedSearch->ViewValue ?>
	</span>
	<?php if (!$report_form->incident_type->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_incident_type" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->incident_type->RadioButtonListHtml(TRUE, "x_incident_type") ?>
		</div>
	</div>
	<div id="tp_x_incident_type" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_incident_type" data-value-separator="<?php echo $report_form->incident_type->DisplayValueSeparatorAttribute() ?>" name="x_incident_type" id="x_incident_type" value="{value}"<?php echo $report_form->incident_type->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_category->Visible) { // incident-category ?>
	<div id="r_incident_category" class="form-group">
		<label for="x_incident_category" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_incident_category"><?php echo $report_form->incident_category->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_incident_category" id="z_incident_category" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->incident_category->CellAttributes() ?>>
			<span id="el_report_form_incident_category">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->incident_category->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->incident_category->AdvancedSearch->ViewValue ?>
	</span>
	<?php if (!$report_form->incident_category->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_incident_category" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->incident_category->RadioButtonListHtml(TRUE, "x_incident_category") ?>
		</div>
	</div>
	<div id="tp_x_incident_category" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_incident_category" data-value-separator="<?php echo $report_form->incident_category->DisplayValueSeparatorAttribute() ?>" name="x_incident_category" id="x_incident_category" value="{value}"<?php echo $report_form->incident_category->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_location->Visible) { // incident_location ?>
	<div id="r_incident_location" class="form-group">
		<label for="x_incident_location" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_incident_location"><?php echo $report_form->incident_location->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_incident_location" id="z_incident_location" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->incident_location->CellAttributes() ?>>
			<span id="el_report_form_incident_location">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" aria-expanded="false"<?php if ($report_form->incident_location->ReadOnly) { ?> readonly<?php } else { ?>data-toggle="dropdown"<?php } ?>>
		<?php echo $report_form->incident_location->AdvancedSearch->ViewValue ?>
	</span>
	<?php if (!$report_form->incident_location->ReadOnly) { ?>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<?php } ?>
	<div id="dsl_x_incident_location" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $report_form->incident_location->RadioButtonListHtml(TRUE, "x_incident_location") ?>
		</div>
	</div>
	<div id="tp_x_incident_location" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_incident_location" data-value-separator="<?php echo $report_form->incident_location->DisplayValueSeparatorAttribute() ?>" name="x_incident_location" id="x_incident_location" value="{value}"<?php echo $report_form->incident_location->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->incident_description->Visible) { // incident_description ?>
	<div id="r_incident_description" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_incident_description"><?php echo $report_form->incident_description->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_incident_description" id="z_incident_description" value="LIKE"></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->incident_description->CellAttributes() ?>>
			<span id="el_report_form_incident_description">
<input type="text" data-table="report_form" data-field="x_incident_description" name="x_incident_description" id="x_incident_description" size="35" maxlength="120" placeholder="<?php echo ew_HtmlEncode($report_form->incident_description->getPlaceHolder()) ?>" value="<?php echo $report_form->incident_description->EditValue ?>"<?php echo $report_form->incident_description->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->_upload->Visible) { // upload ?>
	<div id="r__upload" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form__upload"><?php echo $report_form->_upload->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__upload" id="z__upload" value="LIKE"></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->_upload->CellAttributes() ?>>
			<span id="el_report_form__upload">
<input type="text" data-table="report_form" data-field="x__upload" name="x__upload" id="x__upload" maxlength="120" placeholder="<?php echo ew_HtmlEncode($report_form->_upload->getPlaceHolder()) ?>" value="<?php echo $report_form->_upload->EditValue ?>"<?php echo $report_form->_upload->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label for="x_status" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_status"><?php echo $report_form->status->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->status->CellAttributes() ?>>
			<span id="el_report_form_status">
<select data-table="report_form" data-field="x_status" data-value-separator="<?php echo $report_form->status->DisplayValueSeparatorAttribute() ?>" id="x_status" name="x_status"<?php echo $report_form->status->EditAttributes() ?>>
<?php echo $report_form->status->SelectOptionListHtml("x_status") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->initiator_action->Visible) { // initiator_action ?>
	<div id="r_initiator_action" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_initiator_action"><?php echo $report_form->initiator_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_initiator_action" id="z_initiator_action" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->initiator_action->CellAttributes() ?>>
			<span id="el_report_form_initiator_action">
<div id="tp_x_initiator_action" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_initiator_action" data-value-separator="<?php echo $report_form->initiator_action->DisplayValueSeparatorAttribute() ?>" name="x_initiator_action" id="x_initiator_action" value="{value}"<?php echo $report_form->initiator_action->EditAttributes() ?>></div>
<div id="dsl_x_initiator_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->initiator_action->RadioButtonListHtml(FALSE, "x_initiator_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->initiator_comment->Visible) { // initiator_comment ?>
	<div id="r_initiator_comment" class="form-group">
		<label for="x_initiator_comment" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_initiator_comment"><?php echo $report_form->initiator_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_initiator_comment" id="z_initiator_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->initiator_comment->CellAttributes() ?>>
			<span id="el_report_form_initiator_comment">
<input type="text" data-table="report_form" data-field="x_initiator_comment" name="x_initiator_comment" id="x_initiator_comment" size="35" placeholder="<?php echo ew_HtmlEncode($report_form->initiator_comment->getPlaceHolder()) ?>" value="<?php echo $report_form->initiator_comment->EditValue ?>"<?php echo $report_form->initiator_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->report_by->Visible) { // report_by ?>
	<div id="r_report_by" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_report_by"><?php echo $report_form->report_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_report_by" id="z_report_by" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->report_by->CellAttributes() ?>>
			<span id="el_report_form_report_by">
<?php
$wrkonchange = trim(" " . @$report_form->report_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->report_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_report_by" style="white-space: nowrap; z-index: 8760">
	<input type="text" name="sv_x_report_by" id="sv_x_report_by" value="<?php echo $report_form->report_by->EditValue ?>" size="15" placeholder="<?php echo ew_HtmlEncode($report_form->report_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->report_by->getPlaceHolder()) ?>"<?php echo $report_form->report_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_report_by" data-value-separator="<?php echo $report_form->report_by->DisplayValueSeparatorAttribute() ?>" name="x_report_by" id="x_report_by" value="<?php echo ew_HtmlEncode($report_form->report_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formsearch.CreateAutoSuggest({"id":"x_report_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->datetime_resolved->Visible) { // datetime_resolved ?>
	<div id="r_datetime_resolved" class="form-group">
		<label for="x_datetime_resolved" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_datetime_resolved"><?php echo $report_form->datetime_resolved->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_datetime_resolved" id="z_datetime_resolved" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->datetime_resolved->CellAttributes() ?>>
			<span id="el_report_form_datetime_resolved">
<input type="text" data-table="report_form" data-field="x_datetime_resolved" data-format="11" name="x_datetime_resolved" id="x_datetime_resolved" size="20" placeholder="<?php echo ew_HtmlEncode($report_form->datetime_resolved->getPlaceHolder()) ?>" value="<?php echo $report_form->datetime_resolved->EditValue ?>"<?php echo $report_form->datetime_resolved->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->resolved_action->Visible) { // resolved_action ?>
	<div id="r_resolved_action" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_resolved_action"><?php echo $report_form->resolved_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_resolved_action" id="z_resolved_action" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->resolved_action->CellAttributes() ?>>
			<span id="el_report_form_resolved_action">
<div id="tp_x_resolved_action" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_resolved_action" data-value-separator="<?php echo $report_form->resolved_action->DisplayValueSeparatorAttribute() ?>" name="x_resolved_action" id="x_resolved_action" value="{value}"<?php echo $report_form->resolved_action->EditAttributes() ?>></div>
<div id="dsl_x_resolved_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->resolved_action->RadioButtonListHtml(FALSE, "x_resolved_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->resolved_comment->Visible) { // resolved_comment ?>
	<div id="r_resolved_comment" class="form-group">
		<label for="x_resolved_comment" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_resolved_comment"><?php echo $report_form->resolved_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_resolved_comment" id="z_resolved_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->resolved_comment->CellAttributes() ?>>
			<span id="el_report_form_resolved_comment">
<input type="text" data-table="report_form" data-field="x_resolved_comment" name="x_resolved_comment" id="x_resolved_comment" size="35" placeholder="<?php echo ew_HtmlEncode($report_form->resolved_comment->getPlaceHolder()) ?>" value="<?php echo $report_form->resolved_comment->EditValue ?>"<?php echo $report_form->resolved_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->resolved_by->Visible) { // resolved_by ?>
	<div id="r_resolved_by" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_resolved_by"><?php echo $report_form->resolved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_resolved_by" id="z_resolved_by" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->resolved_by->CellAttributes() ?>>
			<span id="el_report_form_resolved_by">
<?php
$wrkonchange = trim(" " . @$report_form->resolved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->resolved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_resolved_by" style="white-space: nowrap; z-index: 8720">
	<input type="text" name="sv_x_resolved_by" id="sv_x_resolved_by" value="<?php echo $report_form->resolved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->resolved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->resolved_by->getPlaceHolder()) ?>"<?php echo $report_form->resolved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_resolved_by" data-value-separator="<?php echo $report_form->resolved_by->DisplayValueSeparatorAttribute() ?>" name="x_resolved_by" id="x_resolved_by" value="<?php echo ew_HtmlEncode($report_form->resolved_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formsearch.CreateAutoSuggest({"id":"x_resolved_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->datetime_approved->Visible) { // datetime_approved ?>
	<div id="r_datetime_approved" class="form-group">
		<label for="x_datetime_approved" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_datetime_approved"><?php echo $report_form->datetime_approved->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_datetime_approved" id="z_datetime_approved" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->datetime_approved->CellAttributes() ?>>
			<span id="el_report_form_datetime_approved">
<input type="text" data-table="report_form" data-field="x_datetime_approved" data-format="11" name="x_datetime_approved" id="x_datetime_approved" size="17" placeholder="<?php echo ew_HtmlEncode($report_form->datetime_approved->getPlaceHolder()) ?>" value="<?php echo $report_form->datetime_approved->EditValue ?>"<?php echo $report_form->datetime_approved->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->approval_action->Visible) { // approval_action ?>
	<div id="r_approval_action" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_approval_action"><?php echo $report_form->approval_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approval_action" id="z_approval_action" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->approval_action->CellAttributes() ?>>
			<span id="el_report_form_approval_action">
<div id="tp_x_approval_action" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_approval_action" data-value-separator="<?php echo $report_form->approval_action->DisplayValueSeparatorAttribute() ?>" name="x_approval_action" id="x_approval_action" value="{value}"<?php echo $report_form->approval_action->EditAttributes() ?>></div>
<div id="dsl_x_approval_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->approval_action->RadioButtonListHtml(FALSE, "x_approval_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->approval_comment->Visible) { // approval_comment ?>
	<div id="r_approval_comment" class="form-group">
		<label for="x_approval_comment" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_approval_comment"><?php echo $report_form->approval_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_approval_comment" id="z_approval_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->approval_comment->CellAttributes() ?>>
			<span id="el_report_form_approval_comment">
<input type="text" data-table="report_form" data-field="x_approval_comment" name="x_approval_comment" id="x_approval_comment" size="35" placeholder="<?php echo ew_HtmlEncode($report_form->approval_comment->getPlaceHolder()) ?>" value="<?php echo $report_form->approval_comment->EditValue ?>"<?php echo $report_form->approval_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->approved_by->Visible) { // approved_by ?>
	<div id="r_approved_by" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_approved_by"><?php echo $report_form->approved_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_approved_by" id="z_approved_by" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->approved_by->CellAttributes() ?>>
			<span id="el_report_form_approved_by">
<?php
$wrkonchange = trim(" " . @$report_form->approved_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->approved_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_approved_by" style="white-space: nowrap; z-index: 8680">
	<input type="text" name="sv_x_approved_by" id="sv_x_approved_by" value="<?php echo $report_form->approved_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->approved_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->approved_by->getPlaceHolder()) ?>"<?php echo $report_form->approved_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_approved_by" data-value-separator="<?php echo $report_form->approved_by->DisplayValueSeparatorAttribute() ?>" name="x_approved_by" id="x_approved_by" value="<?php echo ew_HtmlEncode($report_form->approved_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formsearch.CreateAutoSuggest({"id":"x_approved_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->closure_action->Visible) { // closure_action ?>
	<div id="r_closure_action" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_closure_action"><?php echo $report_form->closure_action->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_closure_action" id="z_closure_action" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->closure_action->CellAttributes() ?>>
			<span id="el_report_form_closure_action">
<div id="tp_x_closure_action" class="ewTemplate"><input type="radio" data-table="report_form" data-field="x_closure_action" data-value-separator="<?php echo $report_form->closure_action->DisplayValueSeparatorAttribute() ?>" name="x_closure_action" id="x_closure_action" value="{value}"<?php echo $report_form->closure_action->EditAttributes() ?>></div>
<div id="dsl_x_closure_action" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $report_form->closure_action->RadioButtonListHtml(FALSE, "x_closure_action") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->closure_comment->Visible) { // closure_comment ?>
	<div id="r_closure_comment" class="form-group">
		<label for="x_closure_comment" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_closure_comment"><?php echo $report_form->closure_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_closure_comment" id="z_closure_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->closure_comment->CellAttributes() ?>>
			<span id="el_report_form_closure_comment">
<input type="text" data-table="report_form" data-field="x_closure_comment" name="x_closure_comment" id="x_closure_comment" size="35" maxlength="255" placeholder="<?php echo ew_HtmlEncode($report_form->closure_comment->getPlaceHolder()) ?>" value="<?php echo $report_form->closure_comment->EditValue ?>"<?php echo $report_form->closure_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->last_updated_date->Visible) { // last_updated_date ?>
	<div id="r_last_updated_date" class="form-group">
		<label for="x_last_updated_date" class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_last_updated_date"><?php echo $report_form->last_updated_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_last_updated_date" id="z_last_updated_date" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->last_updated_date->CellAttributes() ?>>
			<span id="el_report_form_last_updated_date">
<input type="text" data-table="report_form" data-field="x_last_updated_date" data-format="17" name="x_last_updated_date" id="x_last_updated_date" placeholder="<?php echo ew_HtmlEncode($report_form->last_updated_date->getPlaceHolder()) ?>" value="<?php echo $report_form->last_updated_date->EditValue ?>"<?php echo $report_form->last_updated_date->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($report_form->last_updated_by->Visible) { // last_updated_by ?>
	<div id="r_last_updated_by" class="form-group">
		<label class="<?php echo $report_form_search->LeftColumnClass ?>"><span id="elh_report_form_last_updated_by"><?php echo $report_form->last_updated_by->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_last_updated_by" id="z_last_updated_by" value="="></p>
		</label>
		<div class="<?php echo $report_form_search->RightColumnClass ?>"><div<?php echo $report_form->last_updated_by->CellAttributes() ?>>
			<span id="el_report_form_last_updated_by">
<?php
$wrkonchange = trim(" " . @$report_form->last_updated_by->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$report_form->last_updated_by->EditAttrs["onchange"] = "";
?>
<span id="as_x_last_updated_by" style="white-space: nowrap; z-index: 8640">
	<input type="text" name="sv_x_last_updated_by" id="sv_x_last_updated_by" value="<?php echo $report_form->last_updated_by->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($report_form->last_updated_by->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($report_form->last_updated_by->getPlaceHolder()) ?>"<?php echo $report_form->last_updated_by->EditAttributes() ?>>
</span>
<input type="hidden" data-table="report_form" data-field="x_last_updated_by" data-value-separator="<?php echo $report_form->last_updated_by->DisplayValueSeparatorAttribute() ?>" name="x_last_updated_by" id="x_last_updated_by" value="<?php echo ew_HtmlEncode($report_form->last_updated_by->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
freport_formsearch.CreateAutoSuggest({"id":"x_last_updated_by","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$report_form_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $report_form_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
freport_formsearch.Init();
</script>
<?php
$report_form_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$report_form_search->Page_Terminate();
?>
