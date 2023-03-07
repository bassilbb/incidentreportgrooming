<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "daily_issueinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$daily_issue_list = NULL; // Initialize page object first

class cdaily_issue_list extends cdaily_issue {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DD9080C0-D1CA-431F-831F-CAC8FA61260C}';

	// Table name
	var $TableName = 'daily_issue';

	// Page object name
	var $PageObjName = 'daily_issue_list';

	// Grid form hidden field names
	var $FormName = 'fdaily_issuelist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (daily_issue)
		if (!isset($GLOBALS["daily_issue"]) || get_class($GLOBALS["daily_issue"]) == "cdaily_issue") {
			$GLOBALS["daily_issue"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["daily_issue"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "daily_issueadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "daily_issuedelete.php";
		$this->MultiUpdateUrl = "daily_issueupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'daily_issue', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fdaily_issuelistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->datetime_initiated->SetVisibility();
		$this->incident_id->SetVisibility();
		$this->staffid->SetVisibility();
		$this->staff_id->SetVisibility();
		$this->branch->SetVisibility();
		$this->departments->SetVisibility();
		$this->category->SetVisibility();
		$this->sub_category->SetVisibility();
		$this->incident_description->SetVisibility();
		$this->status->SetVisibility();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $daily_issue;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($daily_issue);
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetupDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetupDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server" && isset($UserProfile))
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fdaily_issuelistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->datetime_initiated->AdvancedSearch->ToJson(), ","); // Field datetime_initiated
		$sFilterList = ew_Concat($sFilterList, $this->incident_id->AdvancedSearch->ToJson(), ","); // Field incident_id
		$sFilterList = ew_Concat($sFilterList, $this->staffid->AdvancedSearch->ToJson(), ","); // Field staffid
		$sFilterList = ew_Concat($sFilterList, $this->staff_id->AdvancedSearch->ToJson(), ","); // Field staff_id
		$sFilterList = ew_Concat($sFilterList, $this->department->AdvancedSearch->ToJson(), ","); // Field department
		$sFilterList = ew_Concat($sFilterList, $this->branch->AdvancedSearch->ToJson(), ","); // Field branch
		$sFilterList = ew_Concat($sFilterList, $this->departments->AdvancedSearch->ToJson(), ","); // Field departments
		$sFilterList = ew_Concat($sFilterList, $this->category->AdvancedSearch->ToJson(), ","); // Field category
		$sFilterList = ew_Concat($sFilterList, $this->sub_category->AdvancedSearch->ToJson(), ","); // Field sub_category
		$sFilterList = ew_Concat($sFilterList, $this->start_date->AdvancedSearch->ToJson(), ","); // Field start_date
		$sFilterList = ew_Concat($sFilterList, $this->end_date->AdvancedSearch->ToJson(), ","); // Field end_date
		$sFilterList = ew_Concat($sFilterList, $this->duration->AdvancedSearch->ToJson(), ","); // Field duration
		$sFilterList = ew_Concat($sFilterList, $this->amount_paid->AdvancedSearch->ToJson(), ","); // Field amount_paid
		$sFilterList = ew_Concat($sFilterList, $this->no_of_people_involved->AdvancedSearch->ToJson(), ","); // Field no_of_people_involved
		$sFilterList = ew_Concat($sFilterList, $this->incident_type->AdvancedSearch->ToJson(), ","); // Field incident_type
		$sFilterList = ew_Concat($sFilterList, $this->incident_category->AdvancedSearch->ToJson(), ","); // Field incident-category
		$sFilterList = ew_Concat($sFilterList, $this->incident_location->AdvancedSearch->ToJson(), ","); // Field incident_location
		$sFilterList = ew_Concat($sFilterList, $this->incident_description->AdvancedSearch->ToJson(), ","); // Field incident_description
		$sFilterList = ew_Concat($sFilterList, $this->_upload->AdvancedSearch->ToJson(), ","); // Field upload
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->initiator_action->AdvancedSearch->ToJson(), ","); // Field initiator_action
		$sFilterList = ew_Concat($sFilterList, $this->initiator_comment->AdvancedSearch->ToJson(), ","); // Field initiator_comment
		$sFilterList = ew_Concat($sFilterList, $this->report_by->AdvancedSearch->ToJson(), ","); // Field report_by
		$sFilterList = ew_Concat($sFilterList, $this->datetime_resolved->AdvancedSearch->ToJson(), ","); // Field datetime_resolved
		$sFilterList = ew_Concat($sFilterList, $this->resolved_action->AdvancedSearch->ToJson(), ","); // Field resolved_action
		$sFilterList = ew_Concat($sFilterList, $this->resolved_comment->AdvancedSearch->ToJson(), ","); // Field resolved_comment
		$sFilterList = ew_Concat($sFilterList, $this->resolved_by->AdvancedSearch->ToJson(), ","); // Field resolved_by
		$sFilterList = ew_Concat($sFilterList, $this->datetime_approved->AdvancedSearch->ToJson(), ","); // Field datetime_approved
		$sFilterList = ew_Concat($sFilterList, $this->approval_action->AdvancedSearch->ToJson(), ","); // Field approval_action
		$sFilterList = ew_Concat($sFilterList, $this->approval_comment->AdvancedSearch->ToJson(), ","); // Field approval_comment
		$sFilterList = ew_Concat($sFilterList, $this->approved_by->AdvancedSearch->ToJson(), ","); // Field approved_by
		$sFilterList = ew_Concat($sFilterList, $this->closure_action->AdvancedSearch->ToJson(), ","); // Field closure_action
		$sFilterList = ew_Concat($sFilterList, $this->closure_comment->AdvancedSearch->ToJson(), ","); // Field closure_comment
		$sFilterList = ew_Concat($sFilterList, $this->last_updated_date->AdvancedSearch->ToJson(), ","); // Field last_updated_date
		$sFilterList = ew_Concat($sFilterList, $this->last_updated_by->AdvancedSearch->ToJson(), ","); // Field last_updated_by
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fdaily_issuelistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field datetime_initiated
		$this->datetime_initiated->AdvancedSearch->SearchValue = @$filter["x_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchOperator = @$filter["z_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchCondition = @$filter["v_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchValue2 = @$filter["y_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->SearchOperator2 = @$filter["w_datetime_initiated"];
		$this->datetime_initiated->AdvancedSearch->Save();

		// Field incident_id
		$this->incident_id->AdvancedSearch->SearchValue = @$filter["x_incident_id"];
		$this->incident_id->AdvancedSearch->SearchOperator = @$filter["z_incident_id"];
		$this->incident_id->AdvancedSearch->SearchCondition = @$filter["v_incident_id"];
		$this->incident_id->AdvancedSearch->SearchValue2 = @$filter["y_incident_id"];
		$this->incident_id->AdvancedSearch->SearchOperator2 = @$filter["w_incident_id"];
		$this->incident_id->AdvancedSearch->Save();

		// Field staffid
		$this->staffid->AdvancedSearch->SearchValue = @$filter["x_staffid"];
		$this->staffid->AdvancedSearch->SearchOperator = @$filter["z_staffid"];
		$this->staffid->AdvancedSearch->SearchCondition = @$filter["v_staffid"];
		$this->staffid->AdvancedSearch->SearchValue2 = @$filter["y_staffid"];
		$this->staffid->AdvancedSearch->SearchOperator2 = @$filter["w_staffid"];
		$this->staffid->AdvancedSearch->Save();

		// Field staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$filter["x_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator = @$filter["z_staff_id"];
		$this->staff_id->AdvancedSearch->SearchCondition = @$filter["v_staff_id"];
		$this->staff_id->AdvancedSearch->SearchValue2 = @$filter["y_staff_id"];
		$this->staff_id->AdvancedSearch->SearchOperator2 = @$filter["w_staff_id"];
		$this->staff_id->AdvancedSearch->Save();

		// Field department
		$this->department->AdvancedSearch->SearchValue = @$filter["x_department"];
		$this->department->AdvancedSearch->SearchOperator = @$filter["z_department"];
		$this->department->AdvancedSearch->SearchCondition = @$filter["v_department"];
		$this->department->AdvancedSearch->SearchValue2 = @$filter["y_department"];
		$this->department->AdvancedSearch->SearchOperator2 = @$filter["w_department"];
		$this->department->AdvancedSearch->Save();

		// Field branch
		$this->branch->AdvancedSearch->SearchValue = @$filter["x_branch"];
		$this->branch->AdvancedSearch->SearchOperator = @$filter["z_branch"];
		$this->branch->AdvancedSearch->SearchCondition = @$filter["v_branch"];
		$this->branch->AdvancedSearch->SearchValue2 = @$filter["y_branch"];
		$this->branch->AdvancedSearch->SearchOperator2 = @$filter["w_branch"];
		$this->branch->AdvancedSearch->Save();

		// Field departments
		$this->departments->AdvancedSearch->SearchValue = @$filter["x_departments"];
		$this->departments->AdvancedSearch->SearchOperator = @$filter["z_departments"];
		$this->departments->AdvancedSearch->SearchCondition = @$filter["v_departments"];
		$this->departments->AdvancedSearch->SearchValue2 = @$filter["y_departments"];
		$this->departments->AdvancedSearch->SearchOperator2 = @$filter["w_departments"];
		$this->departments->AdvancedSearch->Save();

		// Field category
		$this->category->AdvancedSearch->SearchValue = @$filter["x_category"];
		$this->category->AdvancedSearch->SearchOperator = @$filter["z_category"];
		$this->category->AdvancedSearch->SearchCondition = @$filter["v_category"];
		$this->category->AdvancedSearch->SearchValue2 = @$filter["y_category"];
		$this->category->AdvancedSearch->SearchOperator2 = @$filter["w_category"];
		$this->category->AdvancedSearch->Save();

		// Field sub_category
		$this->sub_category->AdvancedSearch->SearchValue = @$filter["x_sub_category"];
		$this->sub_category->AdvancedSearch->SearchOperator = @$filter["z_sub_category"];
		$this->sub_category->AdvancedSearch->SearchCondition = @$filter["v_sub_category"];
		$this->sub_category->AdvancedSearch->SearchValue2 = @$filter["y_sub_category"];
		$this->sub_category->AdvancedSearch->SearchOperator2 = @$filter["w_sub_category"];
		$this->sub_category->AdvancedSearch->Save();

		// Field start_date
		$this->start_date->AdvancedSearch->SearchValue = @$filter["x_start_date"];
		$this->start_date->AdvancedSearch->SearchOperator = @$filter["z_start_date"];
		$this->start_date->AdvancedSearch->SearchCondition = @$filter["v_start_date"];
		$this->start_date->AdvancedSearch->SearchValue2 = @$filter["y_start_date"];
		$this->start_date->AdvancedSearch->SearchOperator2 = @$filter["w_start_date"];
		$this->start_date->AdvancedSearch->Save();

		// Field end_date
		$this->end_date->AdvancedSearch->SearchValue = @$filter["x_end_date"];
		$this->end_date->AdvancedSearch->SearchOperator = @$filter["z_end_date"];
		$this->end_date->AdvancedSearch->SearchCondition = @$filter["v_end_date"];
		$this->end_date->AdvancedSearch->SearchValue2 = @$filter["y_end_date"];
		$this->end_date->AdvancedSearch->SearchOperator2 = @$filter["w_end_date"];
		$this->end_date->AdvancedSearch->Save();

		// Field duration
		$this->duration->AdvancedSearch->SearchValue = @$filter["x_duration"];
		$this->duration->AdvancedSearch->SearchOperator = @$filter["z_duration"];
		$this->duration->AdvancedSearch->SearchCondition = @$filter["v_duration"];
		$this->duration->AdvancedSearch->SearchValue2 = @$filter["y_duration"];
		$this->duration->AdvancedSearch->SearchOperator2 = @$filter["w_duration"];
		$this->duration->AdvancedSearch->Save();

		// Field amount_paid
		$this->amount_paid->AdvancedSearch->SearchValue = @$filter["x_amount_paid"];
		$this->amount_paid->AdvancedSearch->SearchOperator = @$filter["z_amount_paid"];
		$this->amount_paid->AdvancedSearch->SearchCondition = @$filter["v_amount_paid"];
		$this->amount_paid->AdvancedSearch->SearchValue2 = @$filter["y_amount_paid"];
		$this->amount_paid->AdvancedSearch->SearchOperator2 = @$filter["w_amount_paid"];
		$this->amount_paid->AdvancedSearch->Save();

		// Field no_of_people_involved
		$this->no_of_people_involved->AdvancedSearch->SearchValue = @$filter["x_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->SearchOperator = @$filter["z_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->SearchCondition = @$filter["v_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->SearchValue2 = @$filter["y_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->SearchOperator2 = @$filter["w_no_of_people_involved"];
		$this->no_of_people_involved->AdvancedSearch->Save();

		// Field incident_type
		$this->incident_type->AdvancedSearch->SearchValue = @$filter["x_incident_type"];
		$this->incident_type->AdvancedSearch->SearchOperator = @$filter["z_incident_type"];
		$this->incident_type->AdvancedSearch->SearchCondition = @$filter["v_incident_type"];
		$this->incident_type->AdvancedSearch->SearchValue2 = @$filter["y_incident_type"];
		$this->incident_type->AdvancedSearch->SearchOperator2 = @$filter["w_incident_type"];
		$this->incident_type->AdvancedSearch->Save();

		// Field incident-category
		$this->incident_category->AdvancedSearch->SearchValue = @$filter["x_incident_category"];
		$this->incident_category->AdvancedSearch->SearchOperator = @$filter["z_incident_category"];
		$this->incident_category->AdvancedSearch->SearchCondition = @$filter["v_incident_category"];
		$this->incident_category->AdvancedSearch->SearchValue2 = @$filter["y_incident_category"];
		$this->incident_category->AdvancedSearch->SearchOperator2 = @$filter["w_incident_category"];
		$this->incident_category->AdvancedSearch->Save();

		// Field incident_location
		$this->incident_location->AdvancedSearch->SearchValue = @$filter["x_incident_location"];
		$this->incident_location->AdvancedSearch->SearchOperator = @$filter["z_incident_location"];
		$this->incident_location->AdvancedSearch->SearchCondition = @$filter["v_incident_location"];
		$this->incident_location->AdvancedSearch->SearchValue2 = @$filter["y_incident_location"];
		$this->incident_location->AdvancedSearch->SearchOperator2 = @$filter["w_incident_location"];
		$this->incident_location->AdvancedSearch->Save();

		// Field incident_description
		$this->incident_description->AdvancedSearch->SearchValue = @$filter["x_incident_description"];
		$this->incident_description->AdvancedSearch->SearchOperator = @$filter["z_incident_description"];
		$this->incident_description->AdvancedSearch->SearchCondition = @$filter["v_incident_description"];
		$this->incident_description->AdvancedSearch->SearchValue2 = @$filter["y_incident_description"];
		$this->incident_description->AdvancedSearch->SearchOperator2 = @$filter["w_incident_description"];
		$this->incident_description->AdvancedSearch->Save();

		// Field upload
		$this->_upload->AdvancedSearch->SearchValue = @$filter["x__upload"];
		$this->_upload->AdvancedSearch->SearchOperator = @$filter["z__upload"];
		$this->_upload->AdvancedSearch->SearchCondition = @$filter["v__upload"];
		$this->_upload->AdvancedSearch->SearchValue2 = @$filter["y__upload"];
		$this->_upload->AdvancedSearch->SearchOperator2 = @$filter["w__upload"];
		$this->_upload->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field initiator_action
		$this->initiator_action->AdvancedSearch->SearchValue = @$filter["x_initiator_action"];
		$this->initiator_action->AdvancedSearch->SearchOperator = @$filter["z_initiator_action"];
		$this->initiator_action->AdvancedSearch->SearchCondition = @$filter["v_initiator_action"];
		$this->initiator_action->AdvancedSearch->SearchValue2 = @$filter["y_initiator_action"];
		$this->initiator_action->AdvancedSearch->SearchOperator2 = @$filter["w_initiator_action"];
		$this->initiator_action->AdvancedSearch->Save();

		// Field initiator_comment
		$this->initiator_comment->AdvancedSearch->SearchValue = @$filter["x_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->SearchOperator = @$filter["z_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->SearchCondition = @$filter["v_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->SearchValue2 = @$filter["y_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->SearchOperator2 = @$filter["w_initiator_comment"];
		$this->initiator_comment->AdvancedSearch->Save();

		// Field report_by
		$this->report_by->AdvancedSearch->SearchValue = @$filter["x_report_by"];
		$this->report_by->AdvancedSearch->SearchOperator = @$filter["z_report_by"];
		$this->report_by->AdvancedSearch->SearchCondition = @$filter["v_report_by"];
		$this->report_by->AdvancedSearch->SearchValue2 = @$filter["y_report_by"];
		$this->report_by->AdvancedSearch->SearchOperator2 = @$filter["w_report_by"];
		$this->report_by->AdvancedSearch->Save();

		// Field datetime_resolved
		$this->datetime_resolved->AdvancedSearch->SearchValue = @$filter["x_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->SearchOperator = @$filter["z_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->SearchCondition = @$filter["v_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->SearchValue2 = @$filter["y_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->SearchOperator2 = @$filter["w_datetime_resolved"];
		$this->datetime_resolved->AdvancedSearch->Save();

		// Field resolved_action
		$this->resolved_action->AdvancedSearch->SearchValue = @$filter["x_resolved_action"];
		$this->resolved_action->AdvancedSearch->SearchOperator = @$filter["z_resolved_action"];
		$this->resolved_action->AdvancedSearch->SearchCondition = @$filter["v_resolved_action"];
		$this->resolved_action->AdvancedSearch->SearchValue2 = @$filter["y_resolved_action"];
		$this->resolved_action->AdvancedSearch->SearchOperator2 = @$filter["w_resolved_action"];
		$this->resolved_action->AdvancedSearch->Save();

		// Field resolved_comment
		$this->resolved_comment->AdvancedSearch->SearchValue = @$filter["x_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->SearchOperator = @$filter["z_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->SearchCondition = @$filter["v_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->SearchValue2 = @$filter["y_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->SearchOperator2 = @$filter["w_resolved_comment"];
		$this->resolved_comment->AdvancedSearch->Save();

		// Field resolved_by
		$this->resolved_by->AdvancedSearch->SearchValue = @$filter["x_resolved_by"];
		$this->resolved_by->AdvancedSearch->SearchOperator = @$filter["z_resolved_by"];
		$this->resolved_by->AdvancedSearch->SearchCondition = @$filter["v_resolved_by"];
		$this->resolved_by->AdvancedSearch->SearchValue2 = @$filter["y_resolved_by"];
		$this->resolved_by->AdvancedSearch->SearchOperator2 = @$filter["w_resolved_by"];
		$this->resolved_by->AdvancedSearch->Save();

		// Field datetime_approved
		$this->datetime_approved->AdvancedSearch->SearchValue = @$filter["x_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->SearchOperator = @$filter["z_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->SearchCondition = @$filter["v_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->SearchValue2 = @$filter["y_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->SearchOperator2 = @$filter["w_datetime_approved"];
		$this->datetime_approved->AdvancedSearch->Save();

		// Field approval_action
		$this->approval_action->AdvancedSearch->SearchValue = @$filter["x_approval_action"];
		$this->approval_action->AdvancedSearch->SearchOperator = @$filter["z_approval_action"];
		$this->approval_action->AdvancedSearch->SearchCondition = @$filter["v_approval_action"];
		$this->approval_action->AdvancedSearch->SearchValue2 = @$filter["y_approval_action"];
		$this->approval_action->AdvancedSearch->SearchOperator2 = @$filter["w_approval_action"];
		$this->approval_action->AdvancedSearch->Save();

		// Field approval_comment
		$this->approval_comment->AdvancedSearch->SearchValue = @$filter["x_approval_comment"];
		$this->approval_comment->AdvancedSearch->SearchOperator = @$filter["z_approval_comment"];
		$this->approval_comment->AdvancedSearch->SearchCondition = @$filter["v_approval_comment"];
		$this->approval_comment->AdvancedSearch->SearchValue2 = @$filter["y_approval_comment"];
		$this->approval_comment->AdvancedSearch->SearchOperator2 = @$filter["w_approval_comment"];
		$this->approval_comment->AdvancedSearch->Save();

		// Field approved_by
		$this->approved_by->AdvancedSearch->SearchValue = @$filter["x_approved_by"];
		$this->approved_by->AdvancedSearch->SearchOperator = @$filter["z_approved_by"];
		$this->approved_by->AdvancedSearch->SearchCondition = @$filter["v_approved_by"];
		$this->approved_by->AdvancedSearch->SearchValue2 = @$filter["y_approved_by"];
		$this->approved_by->AdvancedSearch->SearchOperator2 = @$filter["w_approved_by"];
		$this->approved_by->AdvancedSearch->Save();

		// Field closure_action
		$this->closure_action->AdvancedSearch->SearchValue = @$filter["x_closure_action"];
		$this->closure_action->AdvancedSearch->SearchOperator = @$filter["z_closure_action"];
		$this->closure_action->AdvancedSearch->SearchCondition = @$filter["v_closure_action"];
		$this->closure_action->AdvancedSearch->SearchValue2 = @$filter["y_closure_action"];
		$this->closure_action->AdvancedSearch->SearchOperator2 = @$filter["w_closure_action"];
		$this->closure_action->AdvancedSearch->Save();

		// Field closure_comment
		$this->closure_comment->AdvancedSearch->SearchValue = @$filter["x_closure_comment"];
		$this->closure_comment->AdvancedSearch->SearchOperator = @$filter["z_closure_comment"];
		$this->closure_comment->AdvancedSearch->SearchCondition = @$filter["v_closure_comment"];
		$this->closure_comment->AdvancedSearch->SearchValue2 = @$filter["y_closure_comment"];
		$this->closure_comment->AdvancedSearch->SearchOperator2 = @$filter["w_closure_comment"];
		$this->closure_comment->AdvancedSearch->Save();

		// Field last_updated_date
		$this->last_updated_date->AdvancedSearch->SearchValue = @$filter["x_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->SearchOperator = @$filter["z_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->SearchCondition = @$filter["v_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->SearchValue2 = @$filter["y_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->SearchOperator2 = @$filter["w_last_updated_date"];
		$this->last_updated_date->AdvancedSearch->Save();

		// Field last_updated_by
		$this->last_updated_by->AdvancedSearch->SearchValue = @$filter["x_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->SearchOperator = @$filter["z_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->SearchCondition = @$filter["v_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->SearchValue2 = @$filter["y_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->SearchOperator2 = @$filter["w_last_updated_by"];
		$this->last_updated_by->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->datetime_initiated, $Default, FALSE); // datetime_initiated
		$this->BuildSearchSql($sWhere, $this->incident_id, $Default, FALSE); // incident_id
		$this->BuildSearchSql($sWhere, $this->staffid, $Default, FALSE); // staffid
		$this->BuildSearchSql($sWhere, $this->staff_id, $Default, FALSE); // staff_id
		$this->BuildSearchSql($sWhere, $this->department, $Default, FALSE); // department
		$this->BuildSearchSql($sWhere, $this->branch, $Default, FALSE); // branch
		$this->BuildSearchSql($sWhere, $this->departments, $Default, FALSE); // departments
		$this->BuildSearchSql($sWhere, $this->category, $Default, FALSE); // category
		$this->BuildSearchSql($sWhere, $this->sub_category, $Default, FALSE); // sub_category
		$this->BuildSearchSql($sWhere, $this->start_date, $Default, FALSE); // start_date
		$this->BuildSearchSql($sWhere, $this->end_date, $Default, FALSE); // end_date
		$this->BuildSearchSql($sWhere, $this->duration, $Default, FALSE); // duration
		$this->BuildSearchSql($sWhere, $this->amount_paid, $Default, FALSE); // amount_paid
		$this->BuildSearchSql($sWhere, $this->no_of_people_involved, $Default, FALSE); // no_of_people_involved
		$this->BuildSearchSql($sWhere, $this->incident_type, $Default, FALSE); // incident_type
		$this->BuildSearchSql($sWhere, $this->incident_category, $Default, FALSE); // incident-category
		$this->BuildSearchSql($sWhere, $this->incident_location, $Default, FALSE); // incident_location
		$this->BuildSearchSql($sWhere, $this->incident_description, $Default, FALSE); // incident_description
		$this->BuildSearchSql($sWhere, $this->_upload, $Default, FALSE); // upload
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->initiator_action, $Default, FALSE); // initiator_action
		$this->BuildSearchSql($sWhere, $this->initiator_comment, $Default, FALSE); // initiator_comment
		$this->BuildSearchSql($sWhere, $this->report_by, $Default, FALSE); // report_by
		$this->BuildSearchSql($sWhere, $this->datetime_resolved, $Default, FALSE); // datetime_resolved
		$this->BuildSearchSql($sWhere, $this->resolved_action, $Default, FALSE); // resolved_action
		$this->BuildSearchSql($sWhere, $this->resolved_comment, $Default, FALSE); // resolved_comment
		$this->BuildSearchSql($sWhere, $this->resolved_by, $Default, FALSE); // resolved_by
		$this->BuildSearchSql($sWhere, $this->datetime_approved, $Default, FALSE); // datetime_approved
		$this->BuildSearchSql($sWhere, $this->approval_action, $Default, FALSE); // approval_action
		$this->BuildSearchSql($sWhere, $this->approval_comment, $Default, FALSE); // approval_comment
		$this->BuildSearchSql($sWhere, $this->approved_by, $Default, FALSE); // approved_by
		$this->BuildSearchSql($sWhere, $this->closure_action, $Default, FALSE); // closure_action
		$this->BuildSearchSql($sWhere, $this->closure_comment, $Default, FALSE); // closure_comment
		$this->BuildSearchSql($sWhere, $this->last_updated_date, $Default, FALSE); // last_updated_date
		$this->BuildSearchSql($sWhere, $this->last_updated_by, $Default, FALSE); // last_updated_by

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->datetime_initiated->AdvancedSearch->Save(); // datetime_initiated
			$this->incident_id->AdvancedSearch->Save(); // incident_id
			$this->staffid->AdvancedSearch->Save(); // staffid
			$this->staff_id->AdvancedSearch->Save(); // staff_id
			$this->department->AdvancedSearch->Save(); // department
			$this->branch->AdvancedSearch->Save(); // branch
			$this->departments->AdvancedSearch->Save(); // departments
			$this->category->AdvancedSearch->Save(); // category
			$this->sub_category->AdvancedSearch->Save(); // sub_category
			$this->start_date->AdvancedSearch->Save(); // start_date
			$this->end_date->AdvancedSearch->Save(); // end_date
			$this->duration->AdvancedSearch->Save(); // duration
			$this->amount_paid->AdvancedSearch->Save(); // amount_paid
			$this->no_of_people_involved->AdvancedSearch->Save(); // no_of_people_involved
			$this->incident_type->AdvancedSearch->Save(); // incident_type
			$this->incident_category->AdvancedSearch->Save(); // incident-category
			$this->incident_location->AdvancedSearch->Save(); // incident_location
			$this->incident_description->AdvancedSearch->Save(); // incident_description
			$this->_upload->AdvancedSearch->Save(); // upload
			$this->status->AdvancedSearch->Save(); // status
			$this->initiator_action->AdvancedSearch->Save(); // initiator_action
			$this->initiator_comment->AdvancedSearch->Save(); // initiator_comment
			$this->report_by->AdvancedSearch->Save(); // report_by
			$this->datetime_resolved->AdvancedSearch->Save(); // datetime_resolved
			$this->resolved_action->AdvancedSearch->Save(); // resolved_action
			$this->resolved_comment->AdvancedSearch->Save(); // resolved_comment
			$this->resolved_by->AdvancedSearch->Save(); // resolved_by
			$this->datetime_approved->AdvancedSearch->Save(); // datetime_approved
			$this->approval_action->AdvancedSearch->Save(); // approval_action
			$this->approval_comment->AdvancedSearch->Save(); // approval_comment
			$this->approved_by->AdvancedSearch->Save(); // approved_by
			$this->closure_action->AdvancedSearch->Save(); // closure_action
			$this->closure_comment->AdvancedSearch->Save(); // closure_comment
			$this->last_updated_date->AdvancedSearch->Save(); // last_updated_date
			$this->last_updated_by->AdvancedSearch->Save(); // last_updated_by
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = $Fld->FldParm();
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->incident_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->staffid, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->incident_description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_upload, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->initiator_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->resolved_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->approval_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->closure_comment, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->datetime_initiated->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staffid->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->staff_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->branch->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->departments->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sub_category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->start_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->end_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->duration->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->amount_paid->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_of_people_involved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_category->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_location->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->incident_description->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_upload->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->initiator_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->report_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->datetime_resolved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resolved_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resolved_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->resolved_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->datetime_approved->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approval_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approval_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approved_by->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->closure_action->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->closure_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->last_updated_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->last_updated_by->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id->AdvancedSearch->UnsetSession();
		$this->datetime_initiated->AdvancedSearch->UnsetSession();
		$this->incident_id->AdvancedSearch->UnsetSession();
		$this->staffid->AdvancedSearch->UnsetSession();
		$this->staff_id->AdvancedSearch->UnsetSession();
		$this->department->AdvancedSearch->UnsetSession();
		$this->branch->AdvancedSearch->UnsetSession();
		$this->departments->AdvancedSearch->UnsetSession();
		$this->category->AdvancedSearch->UnsetSession();
		$this->sub_category->AdvancedSearch->UnsetSession();
		$this->start_date->AdvancedSearch->UnsetSession();
		$this->end_date->AdvancedSearch->UnsetSession();
		$this->duration->AdvancedSearch->UnsetSession();
		$this->amount_paid->AdvancedSearch->UnsetSession();
		$this->no_of_people_involved->AdvancedSearch->UnsetSession();
		$this->incident_type->AdvancedSearch->UnsetSession();
		$this->incident_category->AdvancedSearch->UnsetSession();
		$this->incident_location->AdvancedSearch->UnsetSession();
		$this->incident_description->AdvancedSearch->UnsetSession();
		$this->_upload->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->initiator_action->AdvancedSearch->UnsetSession();
		$this->initiator_comment->AdvancedSearch->UnsetSession();
		$this->report_by->AdvancedSearch->UnsetSession();
		$this->datetime_resolved->AdvancedSearch->UnsetSession();
		$this->resolved_action->AdvancedSearch->UnsetSession();
		$this->resolved_comment->AdvancedSearch->UnsetSession();
		$this->resolved_by->AdvancedSearch->UnsetSession();
		$this->datetime_approved->AdvancedSearch->UnsetSession();
		$this->approval_action->AdvancedSearch->UnsetSession();
		$this->approval_comment->AdvancedSearch->UnsetSession();
		$this->approved_by->AdvancedSearch->UnsetSession();
		$this->closure_action->AdvancedSearch->UnsetSession();
		$this->closure_comment->AdvancedSearch->UnsetSession();
		$this->last_updated_date->AdvancedSearch->UnsetSession();
		$this->last_updated_by->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
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

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->datetime_initiated); // datetime_initiated
			$this->UpdateSort($this->incident_id); // incident_id
			$this->UpdateSort($this->staffid); // staffid
			$this->UpdateSort($this->staff_id); // staff_id
			$this->UpdateSort($this->branch); // branch
			$this->UpdateSort($this->departments); // departments
			$this->UpdateSort($this->category); // category
			$this->UpdateSort($this->sub_category); // sub_category
			$this->UpdateSort($this->incident_description); // incident_description
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->last_updated_date); // last_updated_date
			$this->UpdateSort($this->last_updated_by); // last_updated_by
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->datetime_initiated->setSort("");
				$this->incident_id->setSort("");
				$this->staffid->setSort("");
				$this->staff_id->setSort("");
				$this->branch->setSort("");
				$this->departments->setSort("");
				$this->category->setSort("");
				$this->sub_category->setSort("");
				$this->incident_description->setSort("");
				$this->status->setSort("");
				$this->last_updated_date->setSort("");
				$this->last_updated_by->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdaily_issuelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdaily_issuelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdaily_issuelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdaily_issuelistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = @$_GET["x_id"];
		if ($this->id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// datetime_initiated
		$this->datetime_initiated->AdvancedSearch->SearchValue = @$_GET["x_datetime_initiated"];
		if ($this->datetime_initiated->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime_initiated->AdvancedSearch->SearchOperator = @$_GET["z_datetime_initiated"];

		// incident_id
		$this->incident_id->AdvancedSearch->SearchValue = @$_GET["x_incident_id"];
		if ($this->incident_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_id->AdvancedSearch->SearchOperator = @$_GET["z_incident_id"];

		// staffid
		$this->staffid->AdvancedSearch->SearchValue = @$_GET["x_staffid"];
		if ($this->staffid->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staffid->AdvancedSearch->SearchOperator = @$_GET["z_staffid"];

		// staff_id
		$this->staff_id->AdvancedSearch->SearchValue = @$_GET["x_staff_id"];
		if ($this->staff_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->staff_id->AdvancedSearch->SearchOperator = @$_GET["z_staff_id"];

		// department
		$this->department->AdvancedSearch->SearchValue = @$_GET["x_department"];
		if ($this->department->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->department->AdvancedSearch->SearchOperator = @$_GET["z_department"];

		// branch
		$this->branch->AdvancedSearch->SearchValue = @$_GET["x_branch"];
		if ($this->branch->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->branch->AdvancedSearch->SearchOperator = @$_GET["z_branch"];

		// departments
		$this->departments->AdvancedSearch->SearchValue = @$_GET["x_departments"];
		if ($this->departments->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->departments->AdvancedSearch->SearchOperator = @$_GET["z_departments"];

		// category
		$this->category->AdvancedSearch->SearchValue = @$_GET["x_category"];
		if ($this->category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->category->AdvancedSearch->SearchOperator = @$_GET["z_category"];

		// sub_category
		$this->sub_category->AdvancedSearch->SearchValue = @$_GET["x_sub_category"];
		if ($this->sub_category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->sub_category->AdvancedSearch->SearchOperator = @$_GET["z_sub_category"];

		// start_date
		$this->start_date->AdvancedSearch->SearchValue = @$_GET["x_start_date"];
		if ($this->start_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->start_date->AdvancedSearch->SearchOperator = @$_GET["z_start_date"];

		// end_date
		$this->end_date->AdvancedSearch->SearchValue = @$_GET["x_end_date"];
		if ($this->end_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->end_date->AdvancedSearch->SearchOperator = @$_GET["z_end_date"];

		// duration
		$this->duration->AdvancedSearch->SearchValue = @$_GET["x_duration"];
		if ($this->duration->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->duration->AdvancedSearch->SearchOperator = @$_GET["z_duration"];

		// amount_paid
		$this->amount_paid->AdvancedSearch->SearchValue = @$_GET["x_amount_paid"];
		if ($this->amount_paid->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->amount_paid->AdvancedSearch->SearchOperator = @$_GET["z_amount_paid"];

		// no_of_people_involved
		$this->no_of_people_involved->AdvancedSearch->SearchValue = @$_GET["x_no_of_people_involved"];
		if ($this->no_of_people_involved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->no_of_people_involved->AdvancedSearch->SearchOperator = @$_GET["z_no_of_people_involved"];

		// incident_type
		$this->incident_type->AdvancedSearch->SearchValue = @$_GET["x_incident_type"];
		if ($this->incident_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_type->AdvancedSearch->SearchOperator = @$_GET["z_incident_type"];

		// incident-category
		$this->incident_category->AdvancedSearch->SearchValue = @$_GET["x_incident_category"];
		if ($this->incident_category->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_category->AdvancedSearch->SearchOperator = @$_GET["z_incident_category"];

		// incident_location
		$this->incident_location->AdvancedSearch->SearchValue = @$_GET["x_incident_location"];
		if ($this->incident_location->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_location->AdvancedSearch->SearchOperator = @$_GET["z_incident_location"];

		// incident_description
		$this->incident_description->AdvancedSearch->SearchValue = @$_GET["x_incident_description"];
		if ($this->incident_description->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->incident_description->AdvancedSearch->SearchOperator = @$_GET["z_incident_description"];

		// upload
		$this->_upload->AdvancedSearch->SearchValue = @$_GET["x__upload"];
		if ($this->_upload->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->_upload->AdvancedSearch->SearchOperator = @$_GET["z__upload"];

		// status
		$this->status->AdvancedSearch->SearchValue = @$_GET["x_status"];
		if ($this->status->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// initiator_action
		$this->initiator_action->AdvancedSearch->SearchValue = @$_GET["x_initiator_action"];
		if ($this->initiator_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->initiator_action->AdvancedSearch->SearchOperator = @$_GET["z_initiator_action"];

		// initiator_comment
		$this->initiator_comment->AdvancedSearch->SearchValue = @$_GET["x_initiator_comment"];
		if ($this->initiator_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->initiator_comment->AdvancedSearch->SearchOperator = @$_GET["z_initiator_comment"];

		// report_by
		$this->report_by->AdvancedSearch->SearchValue = @$_GET["x_report_by"];
		if ($this->report_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->report_by->AdvancedSearch->SearchOperator = @$_GET["z_report_by"];

		// datetime_resolved
		$this->datetime_resolved->AdvancedSearch->SearchValue = @$_GET["x_datetime_resolved"];
		if ($this->datetime_resolved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime_resolved->AdvancedSearch->SearchOperator = @$_GET["z_datetime_resolved"];

		// resolved_action
		$this->resolved_action->AdvancedSearch->SearchValue = @$_GET["x_resolved_action"];
		if ($this->resolved_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resolved_action->AdvancedSearch->SearchOperator = @$_GET["z_resolved_action"];

		// resolved_comment
		$this->resolved_comment->AdvancedSearch->SearchValue = @$_GET["x_resolved_comment"];
		if ($this->resolved_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resolved_comment->AdvancedSearch->SearchOperator = @$_GET["z_resolved_comment"];

		// resolved_by
		$this->resolved_by->AdvancedSearch->SearchValue = @$_GET["x_resolved_by"];
		if ($this->resolved_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->resolved_by->AdvancedSearch->SearchOperator = @$_GET["z_resolved_by"];

		// datetime_approved
		$this->datetime_approved->AdvancedSearch->SearchValue = @$_GET["x_datetime_approved"];
		if ($this->datetime_approved->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->datetime_approved->AdvancedSearch->SearchOperator = @$_GET["z_datetime_approved"];

		// approval_action
		$this->approval_action->AdvancedSearch->SearchValue = @$_GET["x_approval_action"];
		if ($this->approval_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approval_action->AdvancedSearch->SearchOperator = @$_GET["z_approval_action"];

		// approval_comment
		$this->approval_comment->AdvancedSearch->SearchValue = @$_GET["x_approval_comment"];
		if ($this->approval_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approval_comment->AdvancedSearch->SearchOperator = @$_GET["z_approval_comment"];

		// approved_by
		$this->approved_by->AdvancedSearch->SearchValue = @$_GET["x_approved_by"];
		if ($this->approved_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approved_by->AdvancedSearch->SearchOperator = @$_GET["z_approved_by"];

		// closure_action
		$this->closure_action->AdvancedSearch->SearchValue = @$_GET["x_closure_action"];
		if ($this->closure_action->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->closure_action->AdvancedSearch->SearchOperator = @$_GET["z_closure_action"];

		// closure_comment
		$this->closure_comment->AdvancedSearch->SearchValue = @$_GET["x_closure_comment"];
		if ($this->closure_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->closure_comment->AdvancedSearch->SearchOperator = @$_GET["z_closure_comment"];

		// last_updated_date
		$this->last_updated_date->AdvancedSearch->SearchValue = @$_GET["x_last_updated_date"];
		if ($this->last_updated_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->last_updated_date->AdvancedSearch->SearchOperator = @$_GET["z_last_updated_date"];

		// last_updated_by
		$this->last_updated_by->AdvancedSearch->SearchValue = @$_GET["x_last_updated_by"];
		if ($this->last_updated_by->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->last_updated_by->AdvancedSearch->SearchOperator = @$_GET["z_last_updated_by"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->id->setDbValue($row['id']);
		$this->datetime_initiated->setDbValue($row['datetime_initiated']);
		$this->incident_id->setDbValue($row['incident_id']);
		$this->staffid->setDbValue($row['staffid']);
		$this->staff_id->setDbValue($row['staff_id']);
		$this->department->setDbValue($row['department']);
		$this->branch->setDbValue($row['branch']);
		$this->departments->setDbValue($row['departments']);
		$this->category->setDbValue($row['category']);
		$this->sub_category->setDbValue($row['sub_category']);
		$this->start_date->setDbValue($row['start_date']);
		$this->end_date->setDbValue($row['end_date']);
		$this->duration->setDbValue($row['duration']);
		$this->amount_paid->setDbValue($row['amount_paid']);
		$this->no_of_people_involved->setDbValue($row['no_of_people_involved']);
		$this->incident_type->setDbValue($row['incident_type']);
		$this->incident_category->setDbValue($row['incident-category']);
		$this->incident_location->setDbValue($row['incident_location']);
		$this->incident_description->setDbValue($row['incident_description']);
		$this->_upload->Upload->DbValue = $row['upload'];
		$this->_upload->setDbValue($this->_upload->Upload->DbValue);
		$this->status->setDbValue($row['status']);
		$this->initiator_action->setDbValue($row['initiator_action']);
		$this->initiator_comment->setDbValue($row['initiator_comment']);
		$this->report_by->setDbValue($row['report_by']);
		$this->datetime_resolved->setDbValue($row['datetime_resolved']);
		$this->resolved_action->setDbValue($row['resolved_action']);
		$this->resolved_comment->setDbValue($row['resolved_comment']);
		$this->resolved_by->setDbValue($row['resolved_by']);
		$this->datetime_approved->setDbValue($row['datetime_approved']);
		$this->approval_action->setDbValue($row['approval_action']);
		$this->approval_comment->setDbValue($row['approval_comment']);
		$this->approved_by->setDbValue($row['approved_by']);
		$this->closure_action->setDbValue($row['closure_action']);
		$this->closure_comment->setDbValue($row['closure_comment']);
		$this->last_updated_date->setDbValue($row['last_updated_date']);
		$this->last_updated_by->setDbValue($row['last_updated_by']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id'] = NULL;
		$row['datetime_initiated'] = NULL;
		$row['incident_id'] = NULL;
		$row['staffid'] = NULL;
		$row['staff_id'] = NULL;
		$row['department'] = NULL;
		$row['branch'] = NULL;
		$row['departments'] = NULL;
		$row['category'] = NULL;
		$row['sub_category'] = NULL;
		$row['start_date'] = NULL;
		$row['end_date'] = NULL;
		$row['duration'] = NULL;
		$row['amount_paid'] = NULL;
		$row['no_of_people_involved'] = NULL;
		$row['incident_type'] = NULL;
		$row['incident-category'] = NULL;
		$row['incident_location'] = NULL;
		$row['incident_description'] = NULL;
		$row['upload'] = NULL;
		$row['status'] = NULL;
		$row['initiator_action'] = NULL;
		$row['initiator_comment'] = NULL;
		$row['report_by'] = NULL;
		$row['datetime_resolved'] = NULL;
		$row['resolved_action'] = NULL;
		$row['resolved_comment'] = NULL;
		$row['resolved_by'] = NULL;
		$row['datetime_approved'] = NULL;
		$row['approval_action'] = NULL;
		$row['approval_comment'] = NULL;
		$row['approved_by'] = NULL;
		$row['closure_action'] = NULL;
		$row['closure_comment'] = NULL;
		$row['last_updated_date'] = NULL;
		$row['last_updated_by'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->datetime_initiated->DbValue = $row['datetime_initiated'];
		$this->incident_id->DbValue = $row['incident_id'];
		$this->staffid->DbValue = $row['staffid'];
		$this->staff_id->DbValue = $row['staff_id'];
		$this->department->DbValue = $row['department'];
		$this->branch->DbValue = $row['branch'];
		$this->departments->DbValue = $row['departments'];
		$this->category->DbValue = $row['category'];
		$this->sub_category->DbValue = $row['sub_category'];
		$this->start_date->DbValue = $row['start_date'];
		$this->end_date->DbValue = $row['end_date'];
		$this->duration->DbValue = $row['duration'];
		$this->amount_paid->DbValue = $row['amount_paid'];
		$this->no_of_people_involved->DbValue = $row['no_of_people_involved'];
		$this->incident_type->DbValue = $row['incident_type'];
		$this->incident_category->DbValue = $row['incident-category'];
		$this->incident_location->DbValue = $row['incident_location'];
		$this->incident_description->DbValue = $row['incident_description'];
		$this->_upload->Upload->DbValue = $row['upload'];
		$this->status->DbValue = $row['status'];
		$this->initiator_action->DbValue = $row['initiator_action'];
		$this->initiator_comment->DbValue = $row['initiator_comment'];
		$this->report_by->DbValue = $row['report_by'];
		$this->datetime_resolved->DbValue = $row['datetime_resolved'];
		$this->resolved_action->DbValue = $row['resolved_action'];
		$this->resolved_comment->DbValue = $row['resolved_comment'];
		$this->resolved_by->DbValue = $row['resolved_by'];
		$this->datetime_approved->DbValue = $row['datetime_approved'];
		$this->approval_action->DbValue = $row['approval_action'];
		$this->approval_comment->DbValue = $row['approval_comment'];
		$this->approved_by->DbValue = $row['approved_by'];
		$this->closure_action->DbValue = $row['closure_action'];
		$this->closure_comment->DbValue = $row['closure_comment'];
		$this->last_updated_date->DbValue = $row['last_updated_date'];
		$this->last_updated_by->DbValue = $row['last_updated_by'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		$this->datetime_initiated->ViewValue = ew_FormatDateTime($this->datetime_initiated->ViewValue, 14);
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

			// incident_description
			$this->incident_description->LinkCustomAttributes = "";
			$this->incident_description->HrefValue = "";
			$this->incident_description->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// last_updated_date
			$this->last_updated_date->LinkCustomAttributes = "";
			$this->last_updated_date->HrefValue = "";
			$this->last_updated_date->TooltipValue = "";

			// last_updated_by
			$this->last_updated_by->LinkCustomAttributes = "";
			$this->last_updated_by->HrefValue = "";
			$this->last_updated_by->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// datetime_initiated
			$this->datetime_initiated->EditAttrs["class"] = "form-control";
			$this->datetime_initiated->EditCustomAttributes = "";
			$this->datetime_initiated->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->datetime_initiated->AdvancedSearch->SearchValue, 14), 14));
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
			$this->staffid->PlaceHolder = ew_RemoveHtml($this->staffid->FldCaption());

			// staff_id
			$this->staff_id->EditAttrs["class"] = "form-control";
			$this->staff_id->EditCustomAttributes = "";

			// branch
			$this->branch->EditCustomAttributes = "";

			// departments
			$this->departments->EditAttrs["class"] = "form-control";
			$this->departments->EditCustomAttributes = "";

			// category
			$this->category->EditAttrs["class"] = "form-control";
			$this->category->EditCustomAttributes = "";

			// sub_category
			$this->sub_category->EditAttrs["class"] = "form-control";
			$this->sub_category->EditCustomAttributes = "";

			// incident_description
			$this->incident_description->EditAttrs["class"] = "form-control";
			$this->incident_description->EditCustomAttributes = "";
			$this->incident_description->EditValue = ew_HtmlEncode($this->incident_description->AdvancedSearch->SearchValue);
			$this->incident_description->PlaceHolder = ew_RemoveHtml($this->incident_description->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";

			// last_updated_date
			$this->last_updated_date->EditAttrs["class"] = "form-control";
			$this->last_updated_date->EditCustomAttributes = "";
			$this->last_updated_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->last_updated_date->AdvancedSearch->SearchValue, 17), 17));
			$this->last_updated_date->PlaceHolder = ew_RemoveHtml($this->last_updated_date->FldCaption());

			// last_updated_by
			$this->last_updated_by->EditAttrs["class"] = "form-control";
			$this->last_updated_by->EditCustomAttributes = "";
			$this->last_updated_by->EditValue = ew_HtmlEncode($this->last_updated_by->AdvancedSearch->SearchValue);
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
		if (!ew_CheckShortEuroDate($this->datetime_initiated->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->datetime_initiated->FldErrMsg());
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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_daily_issue\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_daily_issue',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdaily_issuelist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetupStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($daily_issue_list)) $daily_issue_list = new cdaily_issue_list();

// Page init
$daily_issue_list->Page_Init();

// Page main
$daily_issue_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$daily_issue_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($daily_issue->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdaily_issuelist = new ew_Form("fdaily_issuelist", "list");
fdaily_issuelist.FormKeyCountName = '<?php echo $daily_issue_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdaily_issuelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdaily_issuelist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdaily_issuelist.Lists["x_staffid"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_staffno","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdaily_issuelist.Lists["x_staffid"].Data = "<?php echo $daily_issue_list->staffid->LookupFilterQuery(FALSE, "list") ?>";
fdaily_issuelist.AutoSuggests["x_staffid"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $daily_issue_list->staffid->LookupFilterQuery(TRUE, "list"))) ?>;
fdaily_issuelist.Lists["x_staff_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdaily_issuelist.Lists["x_staff_id"].Data = "<?php echo $daily_issue_list->staff_id->LookupFilterQuery(FALSE, "list") ?>";
fdaily_issuelist.Lists["x_branch"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"branch"};
fdaily_issuelist.Lists["x_branch"].Data = "<?php echo $daily_issue_list->branch->LookupFilterQuery(FALSE, "list") ?>";
fdaily_issuelist.Lists["x_departments"] = {"LinkField":"x_code_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"departments"};
fdaily_issuelist.Lists["x_departments"].Data = "<?php echo $daily_issue_list->departments->LookupFilterQuery(FALSE, "list") ?>";
fdaily_issuelist.Lists["x_category"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":["x_sub_category"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"category"};
fdaily_issuelist.Lists["x_category"].Data = "<?php echo $daily_issue_list->category->LookupFilterQuery(FALSE, "list") ?>";
fdaily_issuelist.Lists["x_sub_category"] = {"LinkField":"x_sub_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_sub_category_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"sub_category"};
fdaily_issuelist.Lists["x_sub_category"].Data = "<?php echo $daily_issue_list->sub_category->LookupFilterQuery(FALSE, "list") ?>";
fdaily_issuelist.Lists["x_status"] = {"LinkField":"x_code","Ajax":true,"AutoFill":false,"DisplayFields":["x_description","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
fdaily_issuelist.Lists["x_status"].Data = "<?php echo $daily_issue_list->status->LookupFilterQuery(FALSE, "list") ?>";
fdaily_issuelist.Lists["x_last_updated_by"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_firstname","x_lastname","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdaily_issuelist.Lists["x_last_updated_by"].Data = "<?php echo $daily_issue_list->last_updated_by->LookupFilterQuery(FALSE, "list") ?>";
fdaily_issuelist.AutoSuggests["x_last_updated_by"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $daily_issue_list->last_updated_by->LookupFilterQuery(TRUE, "list"))) ?>;

// Form object for search
var CurrentSearchForm = fdaily_issuelistsrch = new ew_Form("fdaily_issuelistsrch");

// Validate function for search
fdaily_issuelistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_datetime_initiated");
	if (elm && !ew_CheckShortEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($daily_issue->datetime_initiated->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fdaily_issuelistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdaily_issuelistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($daily_issue->Export == "") { ?>
<div class="ewToolbar">
<?php if ($daily_issue_list->TotalRecs > 0 && $daily_issue_list->ExportOptions->Visible()) { ?>
<?php $daily_issue_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($daily_issue_list->SearchOptions->Visible()) { ?>
<?php $daily_issue_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($daily_issue_list->FilterOptions->Visible()) { ?>
<?php $daily_issue_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $daily_issue_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($daily_issue_list->TotalRecs <= 0)
			$daily_issue_list->TotalRecs = $daily_issue->ListRecordCount();
	} else {
		if (!$daily_issue_list->Recordset && ($daily_issue_list->Recordset = $daily_issue_list->LoadRecordset()))
			$daily_issue_list->TotalRecs = $daily_issue_list->Recordset->RecordCount();
	}
	$daily_issue_list->StartRec = 1;
	if ($daily_issue_list->DisplayRecs <= 0 || ($daily_issue->Export <> "" && $daily_issue->ExportAll)) // Display all records
		$daily_issue_list->DisplayRecs = $daily_issue_list->TotalRecs;
	if (!($daily_issue->Export <> "" && $daily_issue->ExportAll))
		$daily_issue_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$daily_issue_list->Recordset = $daily_issue_list->LoadRecordset($daily_issue_list->StartRec-1, $daily_issue_list->DisplayRecs);

	// Set no record found message
	if ($daily_issue->CurrentAction == "" && $daily_issue_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$daily_issue_list->setWarningMessage(ew_DeniedMsg());
		if ($daily_issue_list->SearchWhere == "0=101")
			$daily_issue_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$daily_issue_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$daily_issue_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($daily_issue->Export == "" && $daily_issue->CurrentAction == "") { ?>
<form name="fdaily_issuelistsrch" id="fdaily_issuelistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($daily_issue_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fdaily_issuelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="daily_issue">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$daily_issue_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$daily_issue->RowType = EW_ROWTYPE_SEARCH;

// Render row
$daily_issue->ResetAttrs();
$daily_issue_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($daily_issue->datetime_initiated->Visible) { // datetime_initiated ?>
	<div id="xsc_datetime_initiated" class="ewCell form-group">
		<label for="x_datetime_initiated" class="ewSearchCaption ewLabel"><?php echo $daily_issue->datetime_initiated->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("<=") ?><input type="hidden" name="z_datetime_initiated" id="z_datetime_initiated" value="<="></span>
		<span class="ewSearchField">
<input type="text" data-table="daily_issue" data-field="x_datetime_initiated" data-format="14" name="x_datetime_initiated" id="x_datetime_initiated" size="18" placeholder="<?php echo ew_HtmlEncode($daily_issue->datetime_initiated->getPlaceHolder()) ?>" value="<?php echo $daily_issue->datetime_initiated->EditValue ?>"<?php echo $daily_issue->datetime_initiated->EditAttributes() ?>>
<?php if (!$daily_issue->datetime_initiated->ReadOnly && !$daily_issue->datetime_initiated->Disabled && !isset($daily_issue->datetime_initiated->EditAttrs["readonly"]) && !isset($daily_issue->datetime_initiated->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdaily_issuelistsrch", "x_datetime_initiated", {"ignoreReadonly":true,"useCurrent":false,"format":14});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($daily_issue_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($daily_issue_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $daily_issue_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($daily_issue_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($daily_issue_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($daily_issue_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($daily_issue_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $daily_issue_list->ShowPageHeader(); ?>
<?php
$daily_issue_list->ShowMessage();
?>
<?php if ($daily_issue_list->TotalRecs > 0 || $daily_issue->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($daily_issue_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> daily_issue">
<?php if ($daily_issue->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($daily_issue->CurrentAction <> "gridadd" && $daily_issue->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($daily_issue_list->Pager)) $daily_issue_list->Pager = new cPrevNextPager($daily_issue_list->StartRec, $daily_issue_list->DisplayRecs, $daily_issue_list->TotalRecs, $daily_issue_list->AutoHidePager) ?>
<?php if ($daily_issue_list->Pager->RecordCount > 0 && $daily_issue_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($daily_issue_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $daily_issue_list->PageUrl() ?>start=<?php echo $daily_issue_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($daily_issue_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $daily_issue_list->PageUrl() ?>start=<?php echo $daily_issue_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $daily_issue_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($daily_issue_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $daily_issue_list->PageUrl() ?>start=<?php echo $daily_issue_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($daily_issue_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $daily_issue_list->PageUrl() ?>start=<?php echo $daily_issue_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $daily_issue_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($daily_issue_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $daily_issue_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $daily_issue_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $daily_issue_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($daily_issue_list->TotalRecs > 0 && (!$daily_issue_list->AutoHidePageSizeSelector || $daily_issue_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="daily_issue">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="5"<?php if ($daily_issue_list->DisplayRecs == 5) { ?> selected<?php } ?>>5</option>
<option value="10"<?php if ($daily_issue_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="15"<?php if ($daily_issue_list->DisplayRecs == 15) { ?> selected<?php } ?>>15</option>
<option value="20"<?php if ($daily_issue_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($daily_issue_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($daily_issue->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($daily_issue_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fdaily_issuelist" id="fdaily_issuelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($daily_issue_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $daily_issue_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="daily_issue">
<div id="gmp_daily_issue" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($daily_issue_list->TotalRecs > 0 || $daily_issue->CurrentAction == "gridedit") { ?>
<table id="tbl_daily_issuelist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$daily_issue_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$daily_issue_list->RenderListOptions();

// Render list options (header, left)
$daily_issue_list->ListOptions->Render("header", "left");
?>
<?php if ($daily_issue->datetime_initiated->Visible) { // datetime_initiated ?>
	<?php if ($daily_issue->SortUrl($daily_issue->datetime_initiated) == "") { ?>
		<th data-name="datetime_initiated" class="<?php echo $daily_issue->datetime_initiated->HeaderCellClass() ?>"><div id="elh_daily_issue_datetime_initiated" class="daily_issue_datetime_initiated"><div class="ewTableHeaderCaption"><?php echo $daily_issue->datetime_initiated->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="datetime_initiated" class="<?php echo $daily_issue->datetime_initiated->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->datetime_initiated) ?>',1);"><div id="elh_daily_issue_datetime_initiated" class="daily_issue_datetime_initiated">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->datetime_initiated->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->datetime_initiated->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->datetime_initiated->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->incident_id->Visible) { // incident_id ?>
	<?php if ($daily_issue->SortUrl($daily_issue->incident_id) == "") { ?>
		<th data-name="incident_id" class="<?php echo $daily_issue->incident_id->HeaderCellClass() ?>"><div id="elh_daily_issue_incident_id" class="daily_issue_incident_id"><div class="ewTableHeaderCaption"><?php echo $daily_issue->incident_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="incident_id" class="<?php echo $daily_issue->incident_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->incident_id) ?>',1);"><div id="elh_daily_issue_incident_id" class="daily_issue_incident_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->incident_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->incident_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->incident_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->staffid->Visible) { // staffid ?>
	<?php if ($daily_issue->SortUrl($daily_issue->staffid) == "") { ?>
		<th data-name="staffid" class="<?php echo $daily_issue->staffid->HeaderCellClass() ?>"><div id="elh_daily_issue_staffid" class="daily_issue_staffid"><div class="ewTableHeaderCaption"><?php echo $daily_issue->staffid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staffid" class="<?php echo $daily_issue->staffid->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->staffid) ?>',1);"><div id="elh_daily_issue_staffid" class="daily_issue_staffid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->staffid->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->staffid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->staffid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->staff_id->Visible) { // staff_id ?>
	<?php if ($daily_issue->SortUrl($daily_issue->staff_id) == "") { ?>
		<th data-name="staff_id" class="<?php echo $daily_issue->staff_id->HeaderCellClass() ?>"><div id="elh_daily_issue_staff_id" class="daily_issue_staff_id"><div class="ewTableHeaderCaption"><?php echo $daily_issue->staff_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="staff_id" class="<?php echo $daily_issue->staff_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->staff_id) ?>',1);"><div id="elh_daily_issue_staff_id" class="daily_issue_staff_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->staff_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->staff_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->staff_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->branch->Visible) { // branch ?>
	<?php if ($daily_issue->SortUrl($daily_issue->branch) == "") { ?>
		<th data-name="branch" class="<?php echo $daily_issue->branch->HeaderCellClass() ?>"><div id="elh_daily_issue_branch" class="daily_issue_branch"><div class="ewTableHeaderCaption"><?php echo $daily_issue->branch->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="branch" class="<?php echo $daily_issue->branch->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->branch) ?>',1);"><div id="elh_daily_issue_branch" class="daily_issue_branch">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->branch->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->branch->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->branch->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->departments->Visible) { // departments ?>
	<?php if ($daily_issue->SortUrl($daily_issue->departments) == "") { ?>
		<th data-name="departments" class="<?php echo $daily_issue->departments->HeaderCellClass() ?>"><div id="elh_daily_issue_departments" class="daily_issue_departments"><div class="ewTableHeaderCaption"><?php echo $daily_issue->departments->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departments" class="<?php echo $daily_issue->departments->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->departments) ?>',1);"><div id="elh_daily_issue_departments" class="daily_issue_departments">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->departments->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->departments->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->departments->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->category->Visible) { // category ?>
	<?php if ($daily_issue->SortUrl($daily_issue->category) == "") { ?>
		<th data-name="category" class="<?php echo $daily_issue->category->HeaderCellClass() ?>"><div id="elh_daily_issue_category" class="daily_issue_category"><div class="ewTableHeaderCaption"><?php echo $daily_issue->category->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="category" class="<?php echo $daily_issue->category->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->category) ?>',1);"><div id="elh_daily_issue_category" class="daily_issue_category">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->category->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->category->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->category->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->sub_category->Visible) { // sub_category ?>
	<?php if ($daily_issue->SortUrl($daily_issue->sub_category) == "") { ?>
		<th data-name="sub_category" class="<?php echo $daily_issue->sub_category->HeaderCellClass() ?>"><div id="elh_daily_issue_sub_category" class="daily_issue_sub_category"><div class="ewTableHeaderCaption"><?php echo $daily_issue->sub_category->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sub_category" class="<?php echo $daily_issue->sub_category->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->sub_category) ?>',1);"><div id="elh_daily_issue_sub_category" class="daily_issue_sub_category">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->sub_category->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->sub_category->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->sub_category->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->incident_description->Visible) { // incident_description ?>
	<?php if ($daily_issue->SortUrl($daily_issue->incident_description) == "") { ?>
		<th data-name="incident_description" class="<?php echo $daily_issue->incident_description->HeaderCellClass() ?>"><div id="elh_daily_issue_incident_description" class="daily_issue_incident_description"><div class="ewTableHeaderCaption"><?php echo $daily_issue->incident_description->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="incident_description" class="<?php echo $daily_issue->incident_description->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->incident_description) ?>',1);"><div id="elh_daily_issue_incident_description" class="daily_issue_incident_description">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->incident_description->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->incident_description->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->incident_description->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->status->Visible) { // status ?>
	<?php if ($daily_issue->SortUrl($daily_issue->status) == "") { ?>
		<th data-name="status" class="<?php echo $daily_issue->status->HeaderCellClass() ?>"><div id="elh_daily_issue_status" class="daily_issue_status"><div class="ewTableHeaderCaption"><?php echo $daily_issue->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $daily_issue->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->status) ?>',1);"><div id="elh_daily_issue_status" class="daily_issue_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->last_updated_date->Visible) { // last_updated_date ?>
	<?php if ($daily_issue->SortUrl($daily_issue->last_updated_date) == "") { ?>
		<th data-name="last_updated_date" class="<?php echo $daily_issue->last_updated_date->HeaderCellClass() ?>"><div id="elh_daily_issue_last_updated_date" class="daily_issue_last_updated_date"><div class="ewTableHeaderCaption"><?php echo $daily_issue->last_updated_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_updated_date" class="<?php echo $daily_issue->last_updated_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->last_updated_date) ?>',1);"><div id="elh_daily_issue_last_updated_date" class="daily_issue_last_updated_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->last_updated_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->last_updated_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->last_updated_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($daily_issue->last_updated_by->Visible) { // last_updated_by ?>
	<?php if ($daily_issue->SortUrl($daily_issue->last_updated_by) == "") { ?>
		<th data-name="last_updated_by" class="<?php echo $daily_issue->last_updated_by->HeaderCellClass() ?>"><div id="elh_daily_issue_last_updated_by" class="daily_issue_last_updated_by"><div class="ewTableHeaderCaption"><?php echo $daily_issue->last_updated_by->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_updated_by" class="<?php echo $daily_issue->last_updated_by->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $daily_issue->SortUrl($daily_issue->last_updated_by) ?>',1);"><div id="elh_daily_issue_last_updated_by" class="daily_issue_last_updated_by">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $daily_issue->last_updated_by->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($daily_issue->last_updated_by->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($daily_issue->last_updated_by->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$daily_issue_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($daily_issue->ExportAll && $daily_issue->Export <> "") {
	$daily_issue_list->StopRec = $daily_issue_list->TotalRecs;
} else {

	// Set the last record to display
	if ($daily_issue_list->TotalRecs > $daily_issue_list->StartRec + $daily_issue_list->DisplayRecs - 1)
		$daily_issue_list->StopRec = $daily_issue_list->StartRec + $daily_issue_list->DisplayRecs - 1;
	else
		$daily_issue_list->StopRec = $daily_issue_list->TotalRecs;
}
$daily_issue_list->RecCnt = $daily_issue_list->StartRec - 1;
if ($daily_issue_list->Recordset && !$daily_issue_list->Recordset->EOF) {
	$daily_issue_list->Recordset->MoveFirst();
	$bSelectLimit = $daily_issue_list->UseSelectLimit;
	if (!$bSelectLimit && $daily_issue_list->StartRec > 1)
		$daily_issue_list->Recordset->Move($daily_issue_list->StartRec - 1);
} elseif (!$daily_issue->AllowAddDeleteRow && $daily_issue_list->StopRec == 0) {
	$daily_issue_list->StopRec = $daily_issue->GridAddRowCount;
}

// Initialize aggregate
$daily_issue->RowType = EW_ROWTYPE_AGGREGATEINIT;
$daily_issue->ResetAttrs();
$daily_issue_list->RenderRow();
while ($daily_issue_list->RecCnt < $daily_issue_list->StopRec) {
	$daily_issue_list->RecCnt++;
	if (intval($daily_issue_list->RecCnt) >= intval($daily_issue_list->StartRec)) {
		$daily_issue_list->RowCnt++;

		// Set up key count
		$daily_issue_list->KeyCount = $daily_issue_list->RowIndex;

		// Init row class and style
		$daily_issue->ResetAttrs();
		$daily_issue->CssClass = "";
		if ($daily_issue->CurrentAction == "gridadd") {
		} else {
			$daily_issue_list->LoadRowValues($daily_issue_list->Recordset); // Load row values
		}
		$daily_issue->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$daily_issue->RowAttrs = array_merge($daily_issue->RowAttrs, array('data-rowindex'=>$daily_issue_list->RowCnt, 'id'=>'r' . $daily_issue_list->RowCnt . '_daily_issue', 'data-rowtype'=>$daily_issue->RowType));

		// Render row
		$daily_issue_list->RenderRow();

		// Render list options
		$daily_issue_list->RenderListOptions();
?>
	<tr<?php echo $daily_issue->RowAttributes() ?>>
<?php

// Render list options (body, left)
$daily_issue_list->ListOptions->Render("body", "left", $daily_issue_list->RowCnt);
?>
	<?php if ($daily_issue->datetime_initiated->Visible) { // datetime_initiated ?>
		<td data-name="datetime_initiated"<?php echo $daily_issue->datetime_initiated->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_datetime_initiated" class="daily_issue_datetime_initiated">
<span<?php echo $daily_issue->datetime_initiated->ViewAttributes() ?>>
<?php echo $daily_issue->datetime_initiated->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->incident_id->Visible) { // incident_id ?>
		<td data-name="incident_id"<?php echo $daily_issue->incident_id->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_incident_id" class="daily_issue_incident_id">
<span<?php echo $daily_issue->incident_id->ViewAttributes() ?>>
<?php echo $daily_issue->incident_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->staffid->Visible) { // staffid ?>
		<td data-name="staffid"<?php echo $daily_issue->staffid->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_staffid" class="daily_issue_staffid">
<span<?php echo $daily_issue->staffid->ViewAttributes() ?>>
<?php echo $daily_issue->staffid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->staff_id->Visible) { // staff_id ?>
		<td data-name="staff_id"<?php echo $daily_issue->staff_id->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_staff_id" class="daily_issue_staff_id">
<span<?php echo $daily_issue->staff_id->ViewAttributes() ?>>
<?php echo $daily_issue->staff_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->branch->Visible) { // branch ?>
		<td data-name="branch"<?php echo $daily_issue->branch->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_branch" class="daily_issue_branch">
<span<?php echo $daily_issue->branch->ViewAttributes() ?>>
<?php echo $daily_issue->branch->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->departments->Visible) { // departments ?>
		<td data-name="departments"<?php echo $daily_issue->departments->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_departments" class="daily_issue_departments">
<span<?php echo $daily_issue->departments->ViewAttributes() ?>>
<?php echo $daily_issue->departments->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->category->Visible) { // category ?>
		<td data-name="category"<?php echo $daily_issue->category->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_category" class="daily_issue_category">
<span<?php echo $daily_issue->category->ViewAttributes() ?>>
<?php echo $daily_issue->category->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->sub_category->Visible) { // sub_category ?>
		<td data-name="sub_category"<?php echo $daily_issue->sub_category->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_sub_category" class="daily_issue_sub_category">
<span<?php echo $daily_issue->sub_category->ViewAttributes() ?>>
<?php echo $daily_issue->sub_category->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->incident_description->Visible) { // incident_description ?>
		<td data-name="incident_description"<?php echo $daily_issue->incident_description->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_incident_description" class="daily_issue_incident_description">
<span<?php echo $daily_issue->incident_description->ViewAttributes() ?>>
<?php echo $daily_issue->incident_description->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->status->Visible) { // status ?>
		<td data-name="status"<?php echo $daily_issue->status->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_status" class="daily_issue_status">
<span<?php echo $daily_issue->status->ViewAttributes() ?>>
<?php echo $daily_issue->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->last_updated_date->Visible) { // last_updated_date ?>
		<td data-name="last_updated_date"<?php echo $daily_issue->last_updated_date->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_last_updated_date" class="daily_issue_last_updated_date">
<span<?php echo $daily_issue->last_updated_date->ViewAttributes() ?>>
<?php echo $daily_issue->last_updated_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($daily_issue->last_updated_by->Visible) { // last_updated_by ?>
		<td data-name="last_updated_by"<?php echo $daily_issue->last_updated_by->CellAttributes() ?>>
<span id="el<?php echo $daily_issue_list->RowCnt ?>_daily_issue_last_updated_by" class="daily_issue_last_updated_by">
<span<?php echo $daily_issue->last_updated_by->ViewAttributes() ?>>
<?php echo $daily_issue->last_updated_by->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$daily_issue_list->ListOptions->Render("body", "right", $daily_issue_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($daily_issue->CurrentAction <> "gridadd")
		$daily_issue_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($daily_issue->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($daily_issue_list->Recordset)
	$daily_issue_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($daily_issue_list->TotalRecs == 0 && $daily_issue->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($daily_issue_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($daily_issue->Export == "") { ?>
<script type="text/javascript">
fdaily_issuelistsrch.FilterList = <?php echo $daily_issue_list->GetFilterList() ?>;
fdaily_issuelistsrch.Init();
fdaily_issuelist.Init();
</script>
<?php } ?>
<?php
$daily_issue_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($daily_issue->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$daily_issue_list->Page_Terminate();
?>
