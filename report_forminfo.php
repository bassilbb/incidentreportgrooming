<?php

// Global variable for table object
$report_form = NULL;

//
// Table class for report_form
//
class creport_form extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $id;
	var $datetime_initiated;
	var $incident_id;
	var $staffid;
	var $staff_id;
	var $department;
	var $branch;
	var $departments;
	var $category;
	var $sub_category;
	var $start_date;
	var $end_date;
	var $duration;
	var $amount_paid;
	var $no_of_people_involved;
	var $incident_type;
	var $incident_category;
	var $incident_location;
	var $incident_description;
	var $_upload;
	var $status;
	var $operational_status;
	var $initiator_action;
	var $initiator_comment;
	var $report_by;
	var $datetime_resolved;
	var $resolved_action;
	var $resolved_comment;
	var $resolved_by;
	var $datetime_approved;
	var $approval_action;
	var $approval_comment;
	var $approved_by;
	var $closure_action;
	var $closure_comment;
	var $last_updated_date;
	var $last_updated_by;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'report_form';
		$this->TableName = 'report_form';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`report_form`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('report_form', 'report_form', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// datetime_initiated
		$this->datetime_initiated = new cField('report_form', 'report_form', 'x_datetime_initiated', 'datetime_initiated', '`datetime_initiated`', ew_CastDateFieldForLike('`datetime_initiated`', 11, "DB"), 135, 11, FALSE, '`datetime_initiated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->datetime_initiated->Sortable = TRUE; // Allow sort
		$this->datetime_initiated->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datetime_initiated'] = &$this->datetime_initiated;

		// incident_id
		$this->incident_id = new cField('report_form', 'report_form', 'x_incident_id', 'incident_id', '`incident_id`', '`incident_id`', 200, -1, FALSE, '`incident_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->incident_id->Sortable = TRUE; // Allow sort
		$this->fields['incident_id'] = &$this->incident_id;

		// staffid
		$this->staffid = new cField('report_form', 'report_form', 'x_staffid', 'staffid', '`staffid`', '`staffid`', 200, -1, FALSE, '`staffid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffid->Sortable = TRUE; // Allow sort
		$this->fields['staffid'] = &$this->staffid;

		// staff_id
		$this->staff_id = new cField('report_form', 'report_form', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->staff_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->staff_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->staff_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staff_id'] = &$this->staff_id;

		// department
		$this->department = new cField('report_form', 'report_form', 'x_department', 'department', '`department`', '`department`', 3, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->department->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->department->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->department->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['department'] = &$this->department;

		// branch
		$this->branch = new cField('report_form', 'report_form', 'x_branch', 'branch', '`branch`', '`branch`', 3, -1, FALSE, '`branch`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->branch->Sortable = TRUE; // Allow sort
		$this->branch->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->branch->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->branch->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['branch'] = &$this->branch;

		// departments
		$this->departments = new cField('report_form', 'report_form', 'x_departments', 'departments', '`departments`', '`departments`', 3, -1, FALSE, '`departments`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->departments->Sortable = TRUE; // Allow sort
		$this->departments->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->departments->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->departments->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['departments'] = &$this->departments;

		// category
		$this->category = new cField('report_form', 'report_form', 'x_category', 'category', '`category`', '`category`', 3, -1, FALSE, '`category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->category->Sortable = TRUE; // Allow sort
		$this->category->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->category->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->category->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['category'] = &$this->category;

		// sub_category
		$this->sub_category = new cField('report_form', 'report_form', 'x_sub_category', 'sub_category', '`sub_category`', '`sub_category`', 3, -1, FALSE, '`sub_category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sub_category->Sortable = TRUE; // Allow sort
		$this->sub_category->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sub_category->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sub_category->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sub_category'] = &$this->sub_category;

		// start_date
		$this->start_date = new cField('report_form', 'report_form', 'x_start_date', 'start_date', '`start_date`', ew_CastDateFieldForLike('`start_date`', 2, "DB"), 133, 2, FALSE, '`start_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->start_date->Sortable = TRUE; // Allow sort
		$this->start_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['start_date'] = &$this->start_date;

		// end_date
		$this->end_date = new cField('report_form', 'report_form', 'x_end_date', 'end_date', '`end_date`', ew_CastDateFieldForLike('`end_date`', 2, "DB"), 133, 2, FALSE, '`end_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->end_date->Sortable = TRUE; // Allow sort
		$this->end_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['end_date'] = &$this->end_date;

		// duration
		$this->duration = new cField('report_form', 'report_form', 'x_duration', 'duration', '`duration`', '`duration`', 3, -1, FALSE, '`duration`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->duration->Sortable = TRUE; // Allow sort
		$this->duration->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['duration'] = &$this->duration;

		// amount_paid
		$this->amount_paid = new cField('report_form', 'report_form', 'x_amount_paid', 'amount_paid', '`amount_paid`', '`amount_paid`', 131, -1, FALSE, '`amount_paid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->amount_paid->Sortable = TRUE; // Allow sort
		$this->amount_paid->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['amount_paid'] = &$this->amount_paid;

		// no_of_people_involved
		$this->no_of_people_involved = new cField('report_form', 'report_form', 'x_no_of_people_involved', 'no_of_people_involved', '`no_of_people_involved`', '`no_of_people_involved`', 3, -1, FALSE, '`no_of_people_involved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_of_people_involved->Sortable = TRUE; // Allow sort
		$this->fields['no_of_people_involved'] = &$this->no_of_people_involved;

		// incident_type
		$this->incident_type = new cField('report_form', 'report_form', 'x_incident_type', 'incident_type', '`incident_type`', '`incident_type`', 3, -1, FALSE, '`incident_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->incident_type->Sortable = TRUE; // Allow sort
		$this->incident_type->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->incident_type->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->incident_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['incident_type'] = &$this->incident_type;

		// incident-category
		$this->incident_category = new cField('report_form', 'report_form', 'x_incident_category', 'incident-category', '`incident-category`', '`incident-category`', 3, -1, FALSE, '`incident-category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->incident_category->Sortable = TRUE; // Allow sort
		$this->incident_category->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->incident_category->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->incident_category->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['incident-category'] = &$this->incident_category;

		// incident_location
		$this->incident_location = new cField('report_form', 'report_form', 'x_incident_location', 'incident_location', '`incident_location`', '`incident_location`', 3, -1, FALSE, '`incident_location`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->incident_location->Sortable = TRUE; // Allow sort
		$this->incident_location->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->incident_location->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->incident_location->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['incident_location'] = &$this->incident_location;

		// incident_description
		$this->incident_description = new cField('report_form', 'report_form', 'x_incident_description', 'incident_description', '`incident_description`', '`incident_description`', 201, -1, FALSE, '`incident_description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->incident_description->Sortable = TRUE; // Allow sort
		$this->fields['incident_description'] = &$this->incident_description;

		// upload
		$this->_upload = new cField('report_form', 'report_form', 'x__upload', 'upload', '`upload`', '`upload`', 200, -1, TRUE, '`upload`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->_upload->Sortable = TRUE; // Allow sort
		$this->_upload->UploadMultiple = TRUE;
		$this->_upload->Upload->UploadMultiple = TRUE;
		$this->_upload->UploadMaxFileCount = 4;
		$this->fields['upload'] = &$this->_upload;

		// status
		$this->status = new cField('report_form', 'report_form', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->status->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// operational_status
		$this->operational_status = new cField('report_form', 'report_form', 'x_operational_status', 'operational_status', '`operational_status`', '`operational_status`', 3, -1, FALSE, '`operational_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->operational_status->Sortable = TRUE; // Allow sort
		$this->operational_status->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->operational_status->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->operational_status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['operational_status'] = &$this->operational_status;

		// initiator_action
		$this->initiator_action = new cField('report_form', 'report_form', 'x_initiator_action', 'initiator_action', '`initiator_action`', '`initiator_action`', 3, -1, FALSE, '`initiator_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->initiator_action->Sortable = TRUE; // Allow sort
		$this->initiator_action->OptionCount = 2;
		$this->initiator_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['initiator_action'] = &$this->initiator_action;

		// initiator_comment
		$this->initiator_comment = new cField('report_form', 'report_form', 'x_initiator_comment', 'initiator_comment', '`initiator_comment`', '`initiator_comment`', 201, -1, FALSE, '`initiator_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->initiator_comment->Sortable = TRUE; // Allow sort
		$this->fields['initiator_comment'] = &$this->initiator_comment;

		// report_by
		$this->report_by = new cField('report_form', 'report_form', 'x_report_by', 'report_by', '`report_by`', '`report_by`', 3, -1, FALSE, '`report_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->report_by->Sortable = TRUE; // Allow sort
		$this->report_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['report_by'] = &$this->report_by;

		// datetime_resolved
		$this->datetime_resolved = new cField('report_form', 'report_form', 'x_datetime_resolved', 'datetime_resolved', '`datetime_resolved`', ew_CastDateFieldForLike('`datetime_resolved`', 11, "DB"), 135, 11, FALSE, '`datetime_resolved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->datetime_resolved->Sortable = TRUE; // Allow sort
		$this->datetime_resolved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datetime_resolved'] = &$this->datetime_resolved;

		// resolved_action
		$this->resolved_action = new cField('report_form', 'report_form', 'x_resolved_action', 'resolved_action', '`resolved_action`', '`resolved_action`', 3, -1, FALSE, '`resolved_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->resolved_action->Sortable = TRUE; // Allow sort
		$this->resolved_action->OptionCount = 3;
		$this->resolved_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['resolved_action'] = &$this->resolved_action;

		// resolved_comment
		$this->resolved_comment = new cField('report_form', 'report_form', 'x_resolved_comment', 'resolved_comment', '`resolved_comment`', '`resolved_comment`', 201, -1, FALSE, '`resolved_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->resolved_comment->Sortable = TRUE; // Allow sort
		$this->fields['resolved_comment'] = &$this->resolved_comment;

		// resolved_by
		$this->resolved_by = new cField('report_form', 'report_form', 'x_resolved_by', 'resolved_by', '`resolved_by`', '`resolved_by`', 3, -1, FALSE, '`resolved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resolved_by->Sortable = TRUE; // Allow sort
		$this->resolved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['resolved_by'] = &$this->resolved_by;

		// datetime_approved
		$this->datetime_approved = new cField('report_form', 'report_form', 'x_datetime_approved', 'datetime_approved', '`datetime_approved`', ew_CastDateFieldForLike('`datetime_approved`', 11, "DB"), 135, 11, FALSE, '`datetime_approved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->datetime_approved->Sortable = TRUE; // Allow sort
		$this->datetime_approved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datetime_approved'] = &$this->datetime_approved;

		// approval_action
		$this->approval_action = new cField('report_form', 'report_form', 'x_approval_action', 'approval_action', '`approval_action`', '`approval_action`', 3, -1, FALSE, '`approval_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->approval_action->Sortable = TRUE; // Allow sort
		$this->approval_action->OptionCount = 3;
		$this->approval_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approval_action'] = &$this->approval_action;

		// approval_comment
		$this->approval_comment = new cField('report_form', 'report_form', 'x_approval_comment', 'approval_comment', '`approval_comment`', '`approval_comment`', 201, -1, FALSE, '`approval_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->approval_comment->Sortable = TRUE; // Allow sort
		$this->fields['approval_comment'] = &$this->approval_comment;

		// approved_by
		$this->approved_by = new cField('report_form', 'report_form', 'x_approved_by', 'approved_by', '`approved_by`', '`approved_by`', 3, -1, FALSE, '`approved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approved_by->Sortable = TRUE; // Allow sort
		$this->approved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approved_by'] = &$this->approved_by;

		// closure_action
		$this->closure_action = new cField('report_form', 'report_form', 'x_closure_action', 'closure_action', '`closure_action`', '`closure_action`', 3, -1, FALSE, '`closure_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->closure_action->Sortable = TRUE; // Allow sort
		$this->closure_action->OptionCount = 2;
		$this->closure_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['closure_action'] = &$this->closure_action;

		// closure_comment
		$this->closure_comment = new cField('report_form', 'report_form', 'x_closure_comment', 'closure_comment', '`closure_comment`', '`closure_comment`', 200, -1, FALSE, '`closure_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->closure_comment->Sortable = TRUE; // Allow sort
		$this->fields['closure_comment'] = &$this->closure_comment;

		// last_updated_date
		$this->last_updated_date = new cField('report_form', 'report_form', 'x_last_updated_date', 'last_updated_date', '`last_updated_date`', ew_CastDateFieldForLike('`last_updated_date`', 17, "DB"), 135, 17, FALSE, '`last_updated_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->last_updated_date->Sortable = TRUE; // Allow sort
		$this->last_updated_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['last_updated_date'] = &$this->last_updated_date;

		// last_updated_by
		$this->last_updated_by = new cField('report_form', 'report_form', 'x_last_updated_by', 'last_updated_by', '`last_updated_by`', '`last_updated_by`', 3, -1, FALSE, '`last_updated_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->last_updated_by->Sortable = TRUE; // Allow sort
		$this->last_updated_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['last_updated_by'] = &$this->last_updated_by;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`report_form`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->id->setDbValue($conn->Insert_ID());
			$rs['id'] = $this->id->DbValue;
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'id';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsold, $rsaudit);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "report_formlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "report_formview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "report_formedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "report_formadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "report_formlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("report_formview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("report_formview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "report_formadd.php?" . $this->UrlParm($parm);
		else
			$url = "report_formadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("report_formedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("report_formadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("report_formdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = $_POST["id"];
			elseif (isset($_GET["id"]))
				$arKeys[] = $_GET["id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->datetime_initiated->setDbValue($rs->fields('datetime_initiated'));
		$this->incident_id->setDbValue($rs->fields('incident_id'));
		$this->staffid->setDbValue($rs->fields('staffid'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->department->setDbValue($rs->fields('department'));
		$this->branch->setDbValue($rs->fields('branch'));
		$this->departments->setDbValue($rs->fields('departments'));
		$this->category->setDbValue($rs->fields('category'));
		$this->sub_category->setDbValue($rs->fields('sub_category'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->end_date->setDbValue($rs->fields('end_date'));
		$this->duration->setDbValue($rs->fields('duration'));
		$this->amount_paid->setDbValue($rs->fields('amount_paid'));
		$this->no_of_people_involved->setDbValue($rs->fields('no_of_people_involved'));
		$this->incident_type->setDbValue($rs->fields('incident_type'));
		$this->incident_category->setDbValue($rs->fields('incident-category'));
		$this->incident_location->setDbValue($rs->fields('incident_location'));
		$this->incident_description->setDbValue($rs->fields('incident_description'));
		$this->_upload->Upload->DbValue = $rs->fields('upload');
		$this->status->setDbValue($rs->fields('status'));
		$this->operational_status->setDbValue($rs->fields('operational_status'));
		$this->initiator_action->setDbValue($rs->fields('initiator_action'));
		$this->initiator_comment->setDbValue($rs->fields('initiator_comment'));
		$this->report_by->setDbValue($rs->fields('report_by'));
		$this->datetime_resolved->setDbValue($rs->fields('datetime_resolved'));
		$this->resolved_action->setDbValue($rs->fields('resolved_action'));
		$this->resolved_comment->setDbValue($rs->fields('resolved_comment'));
		$this->resolved_by->setDbValue($rs->fields('resolved_by'));
		$this->datetime_approved->setDbValue($rs->fields('datetime_approved'));
		$this->approval_action->setDbValue($rs->fields('approval_action'));
		$this->approval_comment->setDbValue($rs->fields('approval_comment'));
		$this->approved_by->setDbValue($rs->fields('approved_by'));
		$this->closure_action->setDbValue($rs->fields('closure_action'));
		$this->closure_comment->setDbValue($rs->fields('closure_comment'));
		$this->last_updated_date->setDbValue($rs->fields('last_updated_date'));
		$this->last_updated_by->setDbValue($rs->fields('last_updated_by'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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
		// operational_status
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
		$lookuptblfilter = "`flag`='2'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
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

		// operational_status
		if (strval($this->operational_status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->operational_status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `operational_status`";
		$sWhereWrk = "";
		$this->operational_status->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->operational_status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->operational_status->ViewValue = $this->operational_status->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->operational_status->ViewValue = $this->operational_status->CurrentValue;
			}
		} else {
			$this->operational_status->ViewValue = NULL;
		}
		$this->operational_status->ViewCustomAttributes = "";

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

		// operational_status
		$this->operational_status->LinkCustomAttributes = "";
		$this->operational_status->HrefValue = "";
		$this->operational_status->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime_initiated
		$this->datetime_initiated->EditAttrs["class"] = "form-control";
		$this->datetime_initiated->EditCustomAttributes = "";
		$this->datetime_initiated->EditValue = ew_FormatDateTime($this->datetime_initiated->CurrentValue, 11);
		$this->datetime_initiated->PlaceHolder = ew_RemoveHtml($this->datetime_initiated->FldCaption());

		// incident_id
		$this->incident_id->EditAttrs["class"] = "form-control";
		$this->incident_id->EditCustomAttributes = "";
		$this->incident_id->EditValue = $this->incident_id->CurrentValue;
		$this->incident_id->PlaceHolder = ew_RemoveHtml($this->incident_id->FldCaption());

		// staffid
		$this->staffid->EditAttrs["class"] = "form-control";
		$this->staffid->EditCustomAttributes = "";
		$this->staffid->EditValue = $this->staffid->CurrentValue;
		$this->staffid->PlaceHolder = ew_RemoveHtml($this->staffid->FldCaption());

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";

		// department
		$this->department->EditCustomAttributes = "";

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

		// start_date
		$this->start_date->EditAttrs["class"] = "form-control";
		$this->start_date->EditCustomAttributes = "";
		$this->start_date->EditValue = ew_FormatDateTime($this->start_date->CurrentValue, 2);
		$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

		// end_date
		$this->end_date->EditAttrs["class"] = "form-control";
		$this->end_date->EditCustomAttributes = "";
		$this->end_date->EditValue = ew_FormatDateTime($this->end_date->CurrentValue, 2);
		$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

		// duration
		$this->duration->EditAttrs["class"] = "form-control";
		$this->duration->EditCustomAttributes = "";
		$this->duration->EditValue = $this->duration->CurrentValue;
		$this->duration->PlaceHolder = ew_RemoveHtml($this->duration->FldCaption());

		// amount_paid
		$this->amount_paid->EditAttrs["class"] = "form-control";
		$this->amount_paid->EditCustomAttributes = "";
		$this->amount_paid->EditValue = $this->amount_paid->CurrentValue;
		$this->amount_paid->PlaceHolder = ew_RemoveHtml($this->amount_paid->FldCaption());
		if (strval($this->amount_paid->EditValue) <> "" && is_numeric($this->amount_paid->EditValue)) $this->amount_paid->EditValue = ew_FormatNumber($this->amount_paid->EditValue, -2, -2, -2, -2);

		// no_of_people_involved
		$this->no_of_people_involved->EditAttrs["class"] = "form-control";
		$this->no_of_people_involved->EditCustomAttributes = "";
		$this->no_of_people_involved->EditValue = $this->no_of_people_involved->CurrentValue;
		$this->no_of_people_involved->PlaceHolder = ew_RemoveHtml($this->no_of_people_involved->FldCaption());

		// incident_type
		$this->incident_type->EditCustomAttributes = "";

		// incident-category
		$this->incident_category->EditCustomAttributes = "";

		// incident_location
		$this->incident_location->EditCustomAttributes = "";

		// incident_description
		$this->incident_description->EditAttrs["class"] = "form-control";
		$this->incident_description->EditCustomAttributes = "";
		$this->incident_description->EditValue = $this->incident_description->CurrentValue;
		$this->incident_description->PlaceHolder = ew_RemoveHtml($this->incident_description->FldCaption());

		// upload
		$this->_upload->EditAttrs["class"] = "form-control";
		$this->_upload->EditCustomAttributes = "";
		$this->_upload->UploadPath = "picture/";
		if (!ew_Empty($this->_upload->Upload->DbValue)) {
			$this->_upload->ImageAlt = $this->_upload->FldAlt();
			$this->_upload->EditValue = $this->_upload->Upload->DbValue;
		} else {
			$this->_upload->EditValue = "";
		}
		if (!ew_Empty($this->_upload->CurrentValue))
				$this->_upload->Upload->FileName = $this->_upload->CurrentValue;

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";

		// operational_status
		$this->operational_status->EditAttrs["class"] = "form-control";
		$this->operational_status->EditCustomAttributes = "";

		// initiator_action
		$this->initiator_action->EditCustomAttributes = "";
		$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

		// initiator_comment
		$this->initiator_comment->EditAttrs["class"] = "form-control";
		$this->initiator_comment->EditCustomAttributes = "";
		$this->initiator_comment->EditValue = $this->initiator_comment->CurrentValue;
		$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

		// report_by
		$this->report_by->EditAttrs["class"] = "form-control";
		$this->report_by->EditCustomAttributes = "";
		$this->report_by->EditValue = $this->report_by->CurrentValue;
		$this->report_by->PlaceHolder = ew_RemoveHtml($this->report_by->FldCaption());

		// datetime_resolved
		$this->datetime_resolved->EditAttrs["class"] = "form-control";
		$this->datetime_resolved->EditCustomAttributes = "";
		$this->datetime_resolved->EditValue = ew_FormatDateTime($this->datetime_resolved->CurrentValue, 11);
		$this->datetime_resolved->PlaceHolder = ew_RemoveHtml($this->datetime_resolved->FldCaption());

		// resolved_action
		$this->resolved_action->EditCustomAttributes = "";
		$this->resolved_action->EditValue = $this->resolved_action->Options(FALSE);

		// resolved_comment
		$this->resolved_comment->EditAttrs["class"] = "form-control";
		$this->resolved_comment->EditCustomAttributes = "";
		$this->resolved_comment->EditValue = $this->resolved_comment->CurrentValue;
		$this->resolved_comment->PlaceHolder = ew_RemoveHtml($this->resolved_comment->FldCaption());

		// resolved_by
		$this->resolved_by->EditAttrs["class"] = "form-control";
		$this->resolved_by->EditCustomAttributes = "";
		$this->resolved_by->EditValue = $this->resolved_by->CurrentValue;
		$this->resolved_by->PlaceHolder = ew_RemoveHtml($this->resolved_by->FldCaption());

		// datetime_approved
		$this->datetime_approved->EditAttrs["class"] = "form-control";
		$this->datetime_approved->EditCustomAttributes = "";
		$this->datetime_approved->EditValue = ew_FormatDateTime($this->datetime_approved->CurrentValue, 11);
		$this->datetime_approved->PlaceHolder = ew_RemoveHtml($this->datetime_approved->FldCaption());

		// approval_action
		$this->approval_action->EditCustomAttributes = "";
		$this->approval_action->EditValue = $this->approval_action->Options(FALSE);

		// approval_comment
		$this->approval_comment->EditAttrs["class"] = "form-control";
		$this->approval_comment->EditCustomAttributes = "";
		$this->approval_comment->EditValue = $this->approval_comment->CurrentValue;
		$this->approval_comment->PlaceHolder = ew_RemoveHtml($this->approval_comment->FldCaption());

		// approved_by
		$this->approved_by->EditAttrs["class"] = "form-control";
		$this->approved_by->EditCustomAttributes = "";
		$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

		// closure_action
		$this->closure_action->EditCustomAttributes = "";
		$this->closure_action->EditValue = $this->closure_action->Options(FALSE);

		// closure_comment
		$this->closure_comment->EditAttrs["class"] = "form-control";
		$this->closure_comment->EditCustomAttributes = "";
		$this->closure_comment->EditValue = $this->closure_comment->CurrentValue;
		$this->closure_comment->PlaceHolder = ew_RemoveHtml($this->closure_comment->FldCaption());

		// last_updated_date
		$this->last_updated_date->EditAttrs["class"] = "form-control";
		$this->last_updated_date->EditCustomAttributes = "";
		$this->last_updated_date->EditValue = ew_FormatDateTime($this->last_updated_date->CurrentValue, 17);
		$this->last_updated_date->PlaceHolder = ew_RemoveHtml($this->last_updated_date->FldCaption());

		// last_updated_by
		$this->last_updated_by->EditAttrs["class"] = "form-control";
		$this->last_updated_by->EditCustomAttributes = "";
		$this->last_updated_by->EditValue = $this->last_updated_by->CurrentValue;
		$this->last_updated_by->PlaceHolder = ew_RemoveHtml($this->last_updated_by->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->datetime_initiated->Exportable) $Doc->ExportCaption($this->datetime_initiated);
					if ($this->incident_id->Exportable) $Doc->ExportCaption($this->incident_id);
					if ($this->staffid->Exportable) $Doc->ExportCaption($this->staffid);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->departments->Exportable) $Doc->ExportCaption($this->departments);
					if ($this->category->Exportable) $Doc->ExportCaption($this->category);
					if ($this->sub_category->Exportable) $Doc->ExportCaption($this->sub_category);
					if ($this->start_date->Exportable) $Doc->ExportCaption($this->start_date);
					if ($this->end_date->Exportable) $Doc->ExportCaption($this->end_date);
					if ($this->duration->Exportable) $Doc->ExportCaption($this->duration);
					if ($this->amount_paid->Exportable) $Doc->ExportCaption($this->amount_paid);
					if ($this->no_of_people_involved->Exportable) $Doc->ExportCaption($this->no_of_people_involved);
					if ($this->incident_type->Exportable) $Doc->ExportCaption($this->incident_type);
					if ($this->incident_category->Exportable) $Doc->ExportCaption($this->incident_category);
					if ($this->incident_location->Exportable) $Doc->ExportCaption($this->incident_location);
					if ($this->incident_description->Exportable) $Doc->ExportCaption($this->incident_description);
					if ($this->_upload->Exportable) $Doc->ExportCaption($this->_upload);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->operational_status->Exportable) $Doc->ExportCaption($this->operational_status);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->initiator_comment->Exportable) $Doc->ExportCaption($this->initiator_comment);
					if ($this->report_by->Exportable) $Doc->ExportCaption($this->report_by);
					if ($this->datetime_resolved->Exportable) $Doc->ExportCaption($this->datetime_resolved);
					if ($this->resolved_action->Exportable) $Doc->ExportCaption($this->resolved_action);
					if ($this->resolved_comment->Exportable) $Doc->ExportCaption($this->resolved_comment);
					if ($this->resolved_by->Exportable) $Doc->ExportCaption($this->resolved_by);
					if ($this->datetime_approved->Exportable) $Doc->ExportCaption($this->datetime_approved);
					if ($this->approval_action->Exportable) $Doc->ExportCaption($this->approval_action);
					if ($this->approval_comment->Exportable) $Doc->ExportCaption($this->approval_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
					if ($this->closure_action->Exportable) $Doc->ExportCaption($this->closure_action);
					if ($this->closure_comment->Exportable) $Doc->ExportCaption($this->closure_comment);
					if ($this->last_updated_date->Exportable) $Doc->ExportCaption($this->last_updated_date);
					if ($this->last_updated_by->Exportable) $Doc->ExportCaption($this->last_updated_by);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->datetime_initiated->Exportable) $Doc->ExportCaption($this->datetime_initiated);
					if ($this->incident_id->Exportable) $Doc->ExportCaption($this->incident_id);
					if ($this->staffid->Exportable) $Doc->ExportCaption($this->staffid);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->departments->Exportable) $Doc->ExportCaption($this->departments);
					if ($this->category->Exportable) $Doc->ExportCaption($this->category);
					if ($this->sub_category->Exportable) $Doc->ExportCaption($this->sub_category);
					if ($this->start_date->Exportable) $Doc->ExportCaption($this->start_date);
					if ($this->end_date->Exportable) $Doc->ExportCaption($this->end_date);
					if ($this->duration->Exportable) $Doc->ExportCaption($this->duration);
					if ($this->amount_paid->Exportable) $Doc->ExportCaption($this->amount_paid);
					if ($this->no_of_people_involved->Exportable) $Doc->ExportCaption($this->no_of_people_involved);
					if ($this->incident_type->Exportable) $Doc->ExportCaption($this->incident_type);
					if ($this->incident_category->Exportable) $Doc->ExportCaption($this->incident_category);
					if ($this->incident_location->Exportable) $Doc->ExportCaption($this->incident_location);
					if ($this->incident_description->Exportable) $Doc->ExportCaption($this->incident_description);
					if ($this->_upload->Exportable) $Doc->ExportCaption($this->_upload);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->operational_status->Exportable) $Doc->ExportCaption($this->operational_status);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->report_by->Exportable) $Doc->ExportCaption($this->report_by);
					if ($this->datetime_resolved->Exportable) $Doc->ExportCaption($this->datetime_resolved);
					if ($this->resolved_action->Exportable) $Doc->ExportCaption($this->resolved_action);
					if ($this->resolved_by->Exportable) $Doc->ExportCaption($this->resolved_by);
					if ($this->datetime_approved->Exportable) $Doc->ExportCaption($this->datetime_approved);
					if ($this->approval_action->Exportable) $Doc->ExportCaption($this->approval_action);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
					if ($this->closure_action->Exportable) $Doc->ExportCaption($this->closure_action);
					if ($this->closure_comment->Exportable) $Doc->ExportCaption($this->closure_comment);
					if ($this->last_updated_date->Exportable) $Doc->ExportCaption($this->last_updated_date);
					if ($this->last_updated_by->Exportable) $Doc->ExportCaption($this->last_updated_by);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->datetime_initiated->Exportable) $Doc->ExportField($this->datetime_initiated);
						if ($this->incident_id->Exportable) $Doc->ExportField($this->incident_id);
						if ($this->staffid->Exportable) $Doc->ExportField($this->staffid);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->departments->Exportable) $Doc->ExportField($this->departments);
						if ($this->category->Exportable) $Doc->ExportField($this->category);
						if ($this->sub_category->Exportable) $Doc->ExportField($this->sub_category);
						if ($this->start_date->Exportable) $Doc->ExportField($this->start_date);
						if ($this->end_date->Exportable) $Doc->ExportField($this->end_date);
						if ($this->duration->Exportable) $Doc->ExportField($this->duration);
						if ($this->amount_paid->Exportable) $Doc->ExportField($this->amount_paid);
						if ($this->no_of_people_involved->Exportable) $Doc->ExportField($this->no_of_people_involved);
						if ($this->incident_type->Exportable) $Doc->ExportField($this->incident_type);
						if ($this->incident_category->Exportable) $Doc->ExportField($this->incident_category);
						if ($this->incident_location->Exportable) $Doc->ExportField($this->incident_location);
						if ($this->incident_description->Exportable) $Doc->ExportField($this->incident_description);
						if ($this->_upload->Exportable) $Doc->ExportField($this->_upload);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->operational_status->Exportable) $Doc->ExportField($this->operational_status);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->initiator_comment->Exportable) $Doc->ExportField($this->initiator_comment);
						if ($this->report_by->Exportable) $Doc->ExportField($this->report_by);
						if ($this->datetime_resolved->Exportable) $Doc->ExportField($this->datetime_resolved);
						if ($this->resolved_action->Exportable) $Doc->ExportField($this->resolved_action);
						if ($this->resolved_comment->Exportable) $Doc->ExportField($this->resolved_comment);
						if ($this->resolved_by->Exportable) $Doc->ExportField($this->resolved_by);
						if ($this->datetime_approved->Exportable) $Doc->ExportField($this->datetime_approved);
						if ($this->approval_action->Exportable) $Doc->ExportField($this->approval_action);
						if ($this->approval_comment->Exportable) $Doc->ExportField($this->approval_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
						if ($this->closure_action->Exportable) $Doc->ExportField($this->closure_action);
						if ($this->closure_comment->Exportable) $Doc->ExportField($this->closure_comment);
						if ($this->last_updated_date->Exportable) $Doc->ExportField($this->last_updated_date);
						if ($this->last_updated_by->Exportable) $Doc->ExportField($this->last_updated_by);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->datetime_initiated->Exportable) $Doc->ExportField($this->datetime_initiated);
						if ($this->incident_id->Exportable) $Doc->ExportField($this->incident_id);
						if ($this->staffid->Exportable) $Doc->ExportField($this->staffid);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->departments->Exportable) $Doc->ExportField($this->departments);
						if ($this->category->Exportable) $Doc->ExportField($this->category);
						if ($this->sub_category->Exportable) $Doc->ExportField($this->sub_category);
						if ($this->start_date->Exportable) $Doc->ExportField($this->start_date);
						if ($this->end_date->Exportable) $Doc->ExportField($this->end_date);
						if ($this->duration->Exportable) $Doc->ExportField($this->duration);
						if ($this->amount_paid->Exportable) $Doc->ExportField($this->amount_paid);
						if ($this->no_of_people_involved->Exportable) $Doc->ExportField($this->no_of_people_involved);
						if ($this->incident_type->Exportable) $Doc->ExportField($this->incident_type);
						if ($this->incident_category->Exportable) $Doc->ExportField($this->incident_category);
						if ($this->incident_location->Exportable) $Doc->ExportField($this->incident_location);
						if ($this->incident_description->Exportable) $Doc->ExportField($this->incident_description);
						if ($this->_upload->Exportable) $Doc->ExportField($this->_upload);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->operational_status->Exportable) $Doc->ExportField($this->operational_status);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->report_by->Exportable) $Doc->ExportField($this->report_by);
						if ($this->datetime_resolved->Exportable) $Doc->ExportField($this->datetime_resolved);
						if ($this->resolved_action->Exportable) $Doc->ExportField($this->resolved_action);
						if ($this->resolved_by->Exportable) $Doc->ExportField($this->resolved_by);
						if ($this->datetime_approved->Exportable) $Doc->ExportField($this->datetime_approved);
						if ($this->approval_action->Exportable) $Doc->ExportField($this->approval_action);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
						if ($this->closure_action->Exportable) $Doc->ExportField($this->closure_action);
						if ($this->closure_comment->Exportable) $Doc->ExportField($this->closure_comment);
						if ($this->last_updated_date->Exportable) $Doc->ExportField($this->last_updated_date);
						if ($this->last_updated_by->Exportable) $Doc->ExportField($this->last_updated_by);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'report_form';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'report_form';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'report_form';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'report_form';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
			if (CurrentUserLevel() == 1) {
				ew_AddFilter($filter, "`status` in (0,1,7) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
			}
			if (CurrentUserLevel() == 2) {

				//ew_AddFilter($filter, "(`status` in (3,7) AND `departments` = '".$_SESSION['Department']."')");
				ew_AddFilter($filter, "(`status` in (3) AND `departments` = '".$_SESSION['Department']."') OR (`status` in (0,1,7) AND `staff_id` = '".$_SESSION['Staff_ID']."')");
			}
			if (CurrentUserLevel() == 3) {

				//ew_AddFilter($filter, "`status` in (4,6)");
				ew_AddFilter($filter, "`status` in (4,6)OR (`status` in (0,1) AND `staff_id` = '".$_SESSION['Staff_ID']."')");
			}
		/*if (CurrentUserLevel() == 1) {
			ew_AddFilter($filter, "`status` in (0,1) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		}
		if (CurrentUserLevel() == 2) {
			ew_AddFilter($filter, "(`status` in (3,7) AND `departments` = '".$_SESSION['Department']."')");
		}
		if (CurrentUserLevel() == 3) {
			ew_AddFilter($filter, "`status` in (4,6)");
		}*/
		/*if (CurrentUserLevel() == 2) {
			ew_AddFilter($filter, "(`status` in (3) AND `department` = '".$_SESSION['Department']."') OR (`status` in (0,1) AND `staff_id` = '".$_SESSION['Staff_ID']."')");
		}*/
		/*if (CurrentUserLevel() == 3) {
			ew_AddFilter($filter, "(`status` in (4) AND `branch` = '".$_SESSION['Branch']."') OR (`status` in (4) AND `staff_id` = '".$_SESSION['Staff_ID']."')");
		}*/

	/*	if (CurrentUserLevel() == 5) {
			ew_AddFilter($filter, "`status` in (6)");
		}*/
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE
		//
		// last Updated User/Date Value

		 	 $rsnew["last_updated_date"] = ew_CurrentDateTime();
		 	 $rsnew["last_updated_by"] = $_SESSION['Staff_ID'];

		// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 1) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 3;
				$rsnew["initiator_action"] = 1;
				$rsnew["report_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Resolution &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

		// Supervisor Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 2) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 4;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Issue Sent for Review &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Manager Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 3) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 3;
				$rsnew["initiator_action"] = 1;
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Investigation &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();

			// last Updated User/Date Value
		 	 $rsnew["last_updated_date"] = ew_CurrentDateTime();
		 	 $rsnew["last_updated_by"] = $_SESSION['Staff_ID'];

		// Officer Only
	if ((CurrentPageID() == "edit" && CurrentUserLevel() == 1) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
			$rsnew["datetime_initiated"] = $now->format('Y-m-d H:i:s');
			$rsnew["datetime_resolved"] = $now->format('Y-m-d H:i:s');
			$rsnew["datetime_approved"] = $now->format('Y-m-d H:i:s');
			$rsnew["report_by"] = $_SESSION['Staff_ID'];
		}	

		// Officer Only
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 1) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1 && $this->status->CurrentValue == 0) {
				$rsnew["status"] = 3;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_action"] = NULL;
				$rsnew["resolved_comment"] = NULL;
				$rsnew["approval_action"] = NULL;
				$rsnew["approval_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Issue sent for Investigation And Resolution &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
			}
		}

			// Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {

				// Save and forward
			if ($this->initiator_action->CurrentValue == 1 && $this->status->CurrentValue == 0 ) {
				$rsnew["status"] = 4;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_action"] = NULL;
				$rsnew["resolved_comment"] = NULL;
				$rsnew["approval_action"] = NULL;
				$rsnew["approval_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Issue Sent for Review &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
			}			
		}

		// Manager
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {

				// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 3;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_action"] = NULL;
				$rsnew["resolved_comment"] = NULL;
				$rsnew["approval_action"] = NULL;
				$rsnew["approval_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Issue sent for Review &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
			}			
		}

		   // Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["datetime_resolved"] = $now->format('Y-m-d H:i:s');
			$rsnew["resolved_by"] = $_SESSION['Staff_ID'];
		}

		// Supervisor - Don't change field values captured by Officer
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["datetime_initiated"] = $rsold["datetime_initiated"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["staffid"] = $rsold["staffid"];
			$rsnew["branch"] = $rsold["branch"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["departments"] = $rsold["departments"];
			$rsnew["start_date"] = $rsold["start_date"];
			$rsnew["end_date"] = $rsold["end_date"];
			$rsnew["duration"] = $rsold["duration"];
			$rsnew["amount_paid"] = $rsold["amount_paid"];
			$rsnew["report_by"] = $rsold["report_by"];
			$rsnew["incident_type"] = $rsold["incident_type"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["incident_location"] = $rsold["incident_location"];
			$rsnew["no_of_people_involved"] = $rsold["no_of_people_involved"];
			$rsnew["category"] = $rsold["category"];
			$rsnew["sub_category"] = $rsold["sub_category"];
			$rsnew["incident-category"] = $rsold["incident-category"];

			//$rsnew["initiator_action"] = $rsold["initiator_action"];
			//$rsnew["initiator_comment"] = $rsold["initiator_comment"];
			//$rsnew["status"] = $rsold["status"];

			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];

			//$rsnew["resolved_action"] = $rsold["resolved_action"];
			//$rsnew["resolved_comment"] = $rsold["resolved_comment"];

		}

		// Manager - Don't change field values captured by Officer
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 3) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["datetime_initiated"] = $rsold["datetime_initiated"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["staffid"] = $rsold["staffid"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["branch"] = $rsold["branch"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["departments"] = $rsold["departments"];
			$rsnew["start_date"] = $rsold["start_date"];
			$rsnew["end_date"] = $rsold["end_date"];
			$rsnew["duration"] = $rsold["duration"];
			$rsnew["amount_paid"] = $rsold["amount_paid"];
			$rsnew["report_by"] = $rsold["report_by"];
			$rsnew["incident_type"] = $rsold["incident_type"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["incident_location"] = $rsold["incident_location"];
			$rsnew["no_of_people_involved"] = $rsold["no_of_people_involved"];
			$rsnew["category"] = $rsold["category"];
			$rsnew["sub_category"] = $rsold["sub_category"];
			$rsnew["incident-category"] = $rsold["incident-category"];
			$rsnew["incident_description"] = $rsold["incident_description"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];
			$rsnew["resolved_action"] = $rsold["resolved_action"];
			$rsnew["resolved_comment"] = $rsold["resolved_comment"];
		}

		// Supervisor
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID'] OR $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
				$rsnew["datetime_resolved"] = $now->format('Y-m-d H:i:s');
				$rsnew["resolved_by"] = $_SESSION['Staff_ID'];

				// Declined by Supervisor

			 	/*	if ($this->resolved_action->CurrentValue == 0) {

					// New 
					if ($this->status->CurrentValue == 3) {			
						$rsnew["status"] = 2;
						$rsnew["resolved_action"] = 0; 

						//$rsnew["initiator_action"] = Null;
						//$rsnew["initiator_comment"] = Null;

					}
					$this->setSuccessMessage("&#x25CE; Issue was declined &#x2718;");
				}*/

				// Returned for Rework by Supervisor
				if ($this->resolved_action->CurrentValue == 1) {

					// New
					if ($this->status->CurrentValue == 3) {
						$rsnew["status"] = 1;					
						$rsnew["resolved_action"] = 1;
						$rsnew["initiator_action"] = Null;
						$rsnew["initiator_comment"] = Null;

						//$rsnew["last_updated_date"] = $_SESSION['Staff_ID'];
					}
					$this->setSuccessMessage("&#x25C9; Issue was returned for rework &#x2714;");
				}

			   	// Transfer by Supervisor
				if ($this->resolved_action->CurrentValue == 3 && $this->status->CurrentValue == 3) {

					// New
					if ($this->status->CurrentValue == 3) {
						$rsnew["status"] = 4;					
						$rsnew["resolved_action"] = 3;
					}
					$this->setSuccessMessage("&#x25C9; Issue was successfully Escalated to Tech Support &#x2714;");
				}	

				// Issue Closed by Supervisor
				if ($this->resolved_action->CurrentValue == 2 && $this->status->CurrentValue == 3) {

					// New
					if ($this->status->CurrentValue == 3) {
						$rsnew["status"] = 5;					
						$rsnew["resolved_action"] = 2;
					}
					$this->setSuccessMessage("&#x25C9; Issue was successfully Resolved and Closed &#x2714;");
				}

				// Closure Action By Supervisor
				if ($this->closure_action->CurrentValue == 4 && $this->status->CurrentValue == 7) {

					// New
					if ($this->status->CurrentValue == 7) {
						$rsnew["status"] = 5;					
						$rsnew["closure_action"] = 4;
					}
					$this->setSuccessMessage("&#x25C9; Issue Confirmed and Closed &#x2714;");
				}	

				// RE-ESCALATED BY SUPERVISOR TO TECH SUPPORT
				if ($this->closure_action->CurrentValue == 5 && $this->status->CurrentValue == 7) {

					// New
					if ($this->status->CurrentValue == 7) {
						$rsnew["status"] = 4;					
						$rsnew["closure_action"] = 5;
					}
					$this->setSuccessMessage("&#x25C9; Issue was Successfully Re-Escalated to Tech Support for Resolution &#x2714;");
				}
			}  	

			// Manager
			if (CurrentPageID() == "edit" && CurrentUserLevel() == 3) {
			   $rsnew["datetime_approved"] = $now->format('Y-m-d H:i:s');
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];

				// Declined by Manager

				 /*	if ($this->approval_action->CurrentValue == 0) {

					// New 
					if ($this->status->CurrentValue == 4) {			
						$rsnew["status"] = 2;
						$rsnew["approval_action"] = 0; 

						//$rsnew["initiator_action"] = Null;
						//$rsnew["initiator_comment"] = Null;

					}
					$this->setSuccessMessage("&#x25CE; Issue was declined &#x2718;");
				}*/

					// Returned for Rework by Supervisor
				if ($this->approval_action->CurrentValue == 0) {

					// New
					if ($this->status->CurrentValue == 4) {
						$rsnew["status"] = 1;					
						$rsnew["approval_action"] = 0;
						$rsnew["initiator_action"] = Null;
						$rsnew["initiator_comment"] = Null;

						//$rsnew["last_updated_date"] = $_SESSION['Staff_ID'];
					}
					$this->setSuccessMessage("&#x25C9; Issue Sent for Corrections &#x2714;");
				}

				// Saved only By Manager
				/*if ($this->approval_action->CurrentValue == 0 && $this->status->CurrentValue == 4) {
				$rsnew["status"] = 4;			
				$rsnew["approval_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
				}*/	

				// Checked only By Manager
				   if ($this->approval_action->CurrentValue == 2 && $this->status->CurrentValue == 4) {

					// New
					if ($this->status->CurrentValue == 4) {
						$rsnew["status"] = 6;					
						$rsnew["approval_action"] = 2;

						//$rsnew["last_updated_date"] = $now->format('Y-m-d H:i:s');
					}
					$this->setSuccessMessage("&#x25C9; Issue Resolution in Progress &#x2714;");
				}	

				// Approved by Manager
				if ($this->approval_action->CurrentValue == 1 && $this->status->CurrentValue == 6) {

					// New
					if ($this->status->CurrentValue == 6) {
						$rsnew["status"] = 7;					
						$rsnew["approval_action"] = 1;
					}
					$this->setSuccessMessage("&#x25C9; Issue was successfully Resolved and Sent for Review & Closure &#x2714;");
				}
			}
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
		if ((CurrentPageID() == "add" || CurrentPageID() == "edit") && ($this->status->CurrentValue == 0 || $this->status->CurrentValue == 1))  {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->datetime_initiated->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->datetime_initiated->EditValue = $this->datetime_initiated->CurrentValue;
			$this->datetime_resolved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->datetime_resolved->EditValue = $this->datetime_resolved->CurrentValue;
		}
		if (CurrentPageID() == "add" && (CurrentUserLevel() == 1 || CurrentUserLevel() == 2 || CurrentUserLevel()   == 3 || CurrentUserLevel() == 4)) {
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
			$this->staffid->CurrentValue = $_SESSION['Staff_ID'];
			$this->staffid->EditValue = $this->staffid->CurrentValue;

			//$this->staffid->CurrentValue = $_SESSION['StffNO'];
			//$this->staffid->EditValue = $this->staffid->CurrentValue;

			$this->department->CurrentValue = $_SESSION['Department'];
			$this->department->EditValue = $this->department->CurrentValue;
			$this->branch->CurrentValue = $_SESSION['Branch'];
			$this->branch->EditValue = $this->branch->CurrentValue;
			$this->report_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->report_by->EditValue = $this->report_by->CurrentValue;
		}
		if (CurrentPageID() == "edit" && (CurrentUserLevel() == 2 )) {
			$this->resolved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->resolved_by->EditValue = $this->resolved_by->CurrentValue;
			$this->last_updated_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->last_updated_by->EditValue = $this->last_updated_by->CurrentValue;
		}
		if (CurrentPageID() == "edit" && (CurrentUserLevel() == 3)) {
			$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->approved_by->EditValue = $this->approved_by->CurrentValue;
			$this->last_updated_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->last_updated_by->EditValue = $this->last_updated_by->CurrentValue;
		}
		if (CurrentPageID() == "add")  {
			$this->incident_id->CurrentValue = $_SESSION['Inc_ID'];
			$this->incident_id->EditValue = $this->incident_id->CurrentValue;

		//	$this->staffid->CurrentValue = $_SESSION['StfID'];
		//	$this->staffid->EditValue = $this->staffid->CurrentValue;
		//	$this->staffno->CurrentValue = $_SESSION['StffNO'];
		//	$this->staffno->EditValue = $this->staffno->CurrentValue;

		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

		if (CurrentPageID() == "add") {
			if (CurrentUserLevel() == 1) {
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->department->ReadOnly = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->Visible = FALSE;
				$this->no_of_people_involved->Visible = TRUE;
				$this->category->Visible = TRUE;
				$this->incident_category->Visible = TRUE;
				$this->incident_id->ReadOnly = TRUE;
				$this->incident_location->Visible = TRUE;
				$this->incident_type->Visible = TRUE;
				$this->sub_category->ReadOnly = FALSE;
				$this->initiator_action->Visible = TRUE;
				$this->initiator_comment->Visible = TRUE;
				$this->datetime_resolved->Visible = FALSE;
				$this->resolved_action->Visible = FALSE;
				$this->resolved_comment->Visible = FALSE;
				$this->resolved_by->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->approval_action->Visible = FALSE;
				$this->approval_comment->Visible = FALSE;
				$this->approved_by->Visible = FALSE;
				$this->closure_action->Visible = FALSE;
			    $this->closure_comment->Visible = FALSE;
			    $this->last_updated_date->Visible = FALSE;
			    $this->last_updated_by->Visible = FALSE;
			}
			if (CurrentUserLevel() == 2 && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->department->ReadOnly = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->Visible = FALSE;
				$this->no_of_people_involved->Visible = TRUE;
				$this->category->Visible = TRUE;
				$this->incident_category->ReadOnly = FALSE;
				$this->incident_location->Visible = TRUE;
				$this->incident_type->Visible = TRUE;
				$this->incident_id->ReadOnly = TRUE;
				$this->sub_category->ReadOnly = FALSE;
				$this->initiator_action->Visible = TRUE;
				$this->initiator_comment->Visible = TRUE;
				$this->datetime_resolved->Visible = FALSE;
				$this->resolved_action->Visible = FALSE;
				$this->resolved_comment->Visible = FALSE;
				$this->resolved_by->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->approval_action->Visible = FALSE;
				$this->approval_comment->Visible = FALSE;
				$this->approved_by->Visible = FALSE;
				$this->closure_action->Visible = FALSE;
			    $this->closure_comment->Visible = FALSE;
			    $this->last_updated_date->Visible = FALSE;
			    $this->last_updated_by->Visible = FALSE;
			}
			if (CurrentUserLevel() == 3 && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->department->ReadOnly = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->Visible = TRUE;
				$this->no_of_people_involved->Visible = TRUE;
				$this->category->ReadOnly = FALSE;
				$this->incident_category->ReadOnly = FALSE;
				$this->incident_location->Visible = TRUE;
				$this->incident_id->ReadOnly = TRUE;
				$this->incident_type->Visible = TRUE;
				$this->sub_category->ReadOnly = FALSE;
				$this->initiator_action->Visible = TRUE;
				$this->initiator_comment->Visible = TRUE;
				$this->datetime_resolved->Visible = FALSE;
				$this->resolved_action->Visible = FALSE;
				$this->resolved_comment->Visible = FALSE;
				$this->resolved_by->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->approval_action->Visible = FALSE;
				$this->approval_comment->Visible = FALSE;
				$this->approved_by->Visible = FALSE;
				$this->closure_action->Visible = FALSE;
			    $this->closure_comment->Visible = FALSE;
			    $this->last_updated_date->Visible = FALSE;
			    $this->last_updated_by->Visible = FALSE;
			}
		}

		// Edit Page
		if (CurrentPageID() == "edit") {
			if (CurrentUserLevel() == 1) {
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->department->ReadOnly = TRUE;
				$this->departments->Visible = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->ReadOnly = TRUE;
				$this->no_of_people_involved->Visible = TRUE;
				$this->category->ReadOnly = FALSE;
				$this->incident_location->Visible = TRUE;
				$this->incident_category->ReadOnly = FALSE;
				$this->incident_id->ReadOnly = TRUE;
				$this->incident_type->Visible = TRUE;
				$this->start_date->Visible = TRUE;
				$this->end_date->Visible = TRUE;
				$this->duration->Visible = TRUE;
				$this->sub_category->ReadOnly = FALSE;
				$this->initiator_action->Visible = TRUE;
				$this->initiator_comment->Visible = TRUE;
				$this->report_by->Visible = FALSE;
				$this->datetime_resolved->Visible = FALSE;
				$this->resolved_action->Visible = FALSE;
				$this->resolved_comment->Visible = FALSE;
				$this->resolved_by->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->approval_action->Visible = FALSE;
				$this->approval_comment->Visible = FALSE;
				$this->approved_by->Visible = FALSE;
				$this->closure_action->Visible = FALSE;
			    $this->closure_comment->Visible = FALSE;
			    $this->last_updated_date->Visible = FALSE;
			    $this->last_updated_by->Visible = FALSE;
			}
			if (CurrentUserLevel() == 1 && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
					$this->datetime_initiated->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->staffid->ReadOnly = TRUE;
					$this->report_by->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->departments->ReadOnly = TRUE;
					$this->branch->ReadOnly = TRUE;
					$this->status->ReadOnly = TRUE;
					$this->no_of_people_involved->ReadOnly = TRUE;
					$this->category->ReadOnly = TRUE;
					$this->incident_category->ReadOnly = TRUE;
					$this->incident_location->ReadOnly = TRUE;
					$this->incident_type->ReadOnly = TRUE;
					$this->incident_id->ReadOnly = TRUE;
					$this->incident_description->ReadOnly = TRUE;
					$this->sub_category->ReadOnly = TRUE;
					$this->start_date->ReadOnly = TRUE;
					$this->end_date->ReadOnly = TRUE;
					$this->duration->ReadOnly = TRUE;
					$this->amount_paid->ReadOnly = TRUE;
					$this->initiator_action->ReadOnly = TRUE;
					$this->initiator_comment->ReadOnly = TRUE;
					$this->report_by->Visible = FALSE;
					$this->datetime_resolved->Visible = FALSE;
					$this->operational_status->ReadOnly = TRUE;
					$this->resolved_action->Visible = TRUE;
					$this->resolved_comment->Visible = TRUE;
					$this->resolved_by->Visible = FALSE;
					$this->datetime_approved->Visible = FALSE;
					$this->approval_action->Visible = FALSE;
					$this->approval_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->last_updated_date->Visible = FALSE;
				    $this->last_updated_by->Visible = FALSE;
				    $this->operational_status->ReadOnly = TRUE;
					if ($this->status->CurrentValue == 7) {
							$this->approval_action->Visible = TRUE;
							$this->approval_comment->Visible = TRUE;
							$this->approval_action->ReadOnly = TRUE;
							$this->approval_comment->ReadOnly = TRUE;
							$this->resolved_action->ReadOnly = TRUE;
						    $this->resolved_comment->ReadOnly = TRUE;
						    $this->closure_action->Visible = TRUE;
				            $this->closure_comment->Visible = TRUE;
						} else {
							$this->approval_action->Visible = FALSE;
							$this->approval_comment->Visible = FALSE;
							$this->approved_by->Visible = FALSE;
							$this->closure_action->Visible = FALSE;
				            $this->closure_comment->Visible = FALSE;
				    	}
				}
			if (CurrentUserLevel() == 2 && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'] && ($this->status->CurrentValue == 0 || $this->status->CurrentValue == 1 )){
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->department->ReadOnly = TRUE;
				$this->departments->Visible = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->ReadOnly = TRUE;
				$this->no_of_people_involved->ReadOnly = TRUE;
				$this->incident_location->ReadOnly = TRUE;
				$this->incident_type->ReadOnly = TRUE;
				$this->incident_id->ReadOnly = TRUE;
				$this->category->ReadOnly = TRUE;
				$this->incident_category->ReadOnly = TRUE;
				$this->sub_category->ReadOnly = TRUE;
				$this->start_date->Visible = TRUE;
				$this->end_date->Visible = TRUE;
				$this->duration->Visible = TRUE;
				$this->initiator_action->Visible = TRUE;
				$this->initiator_comment->Visible = TRUE;
				$this->datetime_resolved->Visible = FALSE;
				$this->resolved_action->Visible = FALSE;
				$this->resolved_comment->Visible = FALSE;
				$this->resolved_by->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->approval_action->Visible = FALSE;
				$this->approval_comment->Visible = FALSE;
				$this->approved_by->Visible = FALSE;
				$this->closure_action->Visible = FALSE;
				$this->closure_comment->Visible = FALSE;
				$this->last_updated_date->Visible = FALSE;
				$this->last_updated_by->Visible = FALSE;
			}
			if (CurrentUserLevel() == 2 && $this->status->CurrentValue == 7 && $this->report_by->CurrentValue == $_SESSION['Staff_ID']){
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->report_by->ReadOnly = TRUE;
				$this->department->ReadOnly = TRUE;
				$this->departments->ReadOnly = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->ReadOnly = TRUE;
				$this->no_of_people_involved->ReadOnly = TRUE;
				$this->category->ReadOnly = TRUE;
				$this->incident_category->ReadOnly = TRUE;
				$this->incident_location->ReadOnly = TRUE;
				$this->incident_type->ReadOnly = TRUE;
				$this->incident_id->ReadOnly = TRUE;
				$this->incident_description->ReadOnly = TRUE;
				$this->sub_category->ReadOnly = TRUE;
				$this->start_date->ReadOnly = TRUE;
				$this->end_date->ReadOnly = TRUE;
				$this->duration->ReadOnly = TRUE;
				$this->amount_paid->ReadOnly = TRUE;
				$this->initiator_action->ReadOnly = TRUE;
				$this->initiator_comment->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->datetime_resolved->Visible = FALSE;
				$this->operational_status->ReadOnly = TRUE;
				$this->resolved_action->ReadOnly = TRUE;
				$this->resolved_comment->ReadOnly = TRUE;
				$this->resolved_by->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->approval_action->ReadOnly = TRUE;
				$this->approval_comment->ReadOnly = TRUE;
				$this->approved_by->Visible = FALSE;
				$this->last_updated_date->Visible = FALSE;
			    $this->last_updated_by->Visible = FALSE;
			    $this->operational_status->ReadOnly = TRUE;
				$this->closure_action->Visible = TRUE;
			    $this->closure_comment->Visible = TRUE;
			}
				if (CurrentUserLevel() == 2 && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'] || $this->staff_id->CurrentValue == $_SESSION['Staff_ID'] )) {
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->report_by->ReadOnly = TRUE;
				$this->department->ReadOnly = TRUE;
				$this->departments->ReadOnly = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->ReadOnly = TRUE;
				$this->no_of_people_involved->ReadOnly = TRUE;
				$this->category->ReadOnly = TRUE;
				$this->incident_category->ReadOnly = TRUE;
				$this->incident_location->ReadOnly = TRUE;
				$this->incident_type->ReadOnly = TRUE;
				$this->incident_id->ReadOnly = TRUE;
				$this->incident_description->ReadOnly = TRUE;
				$this->sub_category->ReadOnly = TRUE;
				$this->start_date->ReadOnly = TRUE;
				$this->end_date->ReadOnly = TRUE;
				$this->duration->ReadOnly = TRUE;
				$this->amount_paid->ReadOnly = TRUE;
				$this->initiator_action->ReadOnly = TRUE;
				$this->initiator_comment->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->datetime_resolved->Visible = FALSE;
				$this->operational_status->ReadOnly = TRUE;
				$this->resolved_action->Visible = TRUE;
				$this->resolved_comment->Visible = TRUE;
				$this->resolved_by->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->approval_action->Visible = FALSE;
				$this->approval_comment->Visible = FALSE;
				$this->approved_by->Visible = FALSE;
				$this->last_updated_date->Visible = FALSE;
			    $this->last_updated_by->Visible = FALSE;
			    $this->operational_status->ReadOnly = TRUE;
				if ($this->status->CurrentValue == 7) {
						$this->approval_action->Visible = TRUE;
						$this->approval_comment->Visible = TRUE;
						$this->approval_action->ReadOnly = TRUE;
						$this->approval_comment->ReadOnly = TRUE;
						$this->resolved_action->ReadOnly = TRUE;
					    $this->resolved_comment->ReadOnly = TRUE;
					    $this->closure_action->Visible = TRUE;
			            $this->closure_comment->Visible = TRUE;
					} else {
						$this->approval_action->Visible = FALSE;
						$this->approval_comment->Visible = FALSE;
						$this->approved_by->Visible = FALSE;
						$this->closure_action->Visible = FALSE;
			            $this->closure_comment->Visible = FALSE;
			    	}
			}
			if (CurrentUserLevel() == 3 && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->department->ReadOnly = TRUE;
				$this->departments->Visible = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->ReadOnly = TRUE;
				$this->no_of_people_involved->ReadOnly = TRUE;
				$this->incident_location->ReadOnly = TRUE;
				$this->incident_type->ReadOnly = TRUE;
				$this->incident_id->ReadOnly = TRUE;
				$this->category->ReadOnly = TRUE;
				$this->incident_category->ReadOnly = TRUE;
				$this->sub_category->ReadOnly = TRUE;
				$this->start_date->Visible = TRUE;
				$this->end_date->Visible = TRUE;
				$this->duration->Visible = TRUE;
				$this->initiator_action->Visible = TRUE;
				$this->initiator_comment->Visible = TRUE;
				$this->datetime_resolved->Visible = FALSE;
				$this->resolved_action->Visible = FALSE;
				$this->resolved_comment->Visible = FALSE;
				$this->resolved_by->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->approval_action->Visible = FALSE;
				$this->approval_comment->Visible = FALSE;
				$this->approved_by->Visible = FALSE;
				$this->closure_action->Visible = FALSE;
			    $this->closure_comment->Visible = FALSE;
			    $this->last_updated_date->Visible = FALSE;
			    $this->last_updated_by->Visible = FALSE;
			}
			if (CurrentUserLevel() == 3 && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']) {
				$this->datetime_initiated->ReadOnly = TRUE;
				$this->staff_id->ReadOnly = TRUE;
				$this->staffid->ReadOnly = TRUE;
				$this->report_by->ReadOnly = TRUE;
				$this->department->ReadOnly = TRUE;
				$this->departments->ReadOnly = TRUE;
				$this->branch->ReadOnly = TRUE;
				$this->status->ReadOnly = TRUE;
				$this->no_of_people_involved->ReadOnly = TRUE;
				$this->incident_location->ReadOnly = TRUE;
				$this->incident_id->ReadOnly = TRUE;
				$this->incident_type->ReadOnly = TRUE;
				$this->category->ReadOnly = TRUE;
				$this->incident_category->ReadOnly = TRUE;
				$this->incident_description->ReadOnly = TRUE;
				$this->sub_category->ReadOnly = TRUE;
				$this->start_date->ReadOnly = TRUE;
				$this->end_date->ReadOnly = TRUE;
				$this->duration->ReadOnly = TRUE;
				$this->amount_paid->ReadOnly = TRUE;
				$this->initiator_action->ReadOnly = TRUE;
				$this->initiator_comment->ReadOnly = TRUE;
				$this->report_by->Visible = FALSE;
				$this->resolved_action->ReadOnly = TRUE;
				$this->resolved_comment->ReadOnly = TRUE;
				$this->datetime_resolved->Visible = FALSE;
				$this->datetime_approved->Visible = FALSE;
				$this->last_updated_date->Visible = FALSE;
			    $this->last_updated_by->Visible = FALSE;
			    $this->operational_status->ReadOnly = TRUE;
				if ($this->department->CurrentValue == 2) {
					$this->datetime_resolved->ReadOnly = FALSE;
					$this->resolved_action->Visible = FALSE;
					$this->resolved_comment->Visible = FALSE;
					$this->resolved_by->Visible = FALSE;
				} else {
			    	$this->datetime_resolved->ReadOnly = TRUE;
					$this->resolved_action->ReadOnly = TRUE;
					$this->resolved_comment->ReadOnly = TRUE;
					$this->resolved_by->Visible = FALSE;
					$this->report_by->Visible = FALSE;
					$this->approval_action->Visible = TRUE;
					$this->approval_comment->Visible = TRUE;
					$this->approved_by->Visible = FALSE;
					$this->closure_action->Visible = FALSE;
					$this->closure_comment->Visible = FALSE;
				}
			}
		}

		// Highligh rows in color based on the status
		if (CurrentPageID() == "list") {

			//$this->branch_code->Visible = FALSE;
			if ($this->status->CurrentValue == 1) {
				$this->id->CellCssStyle = "color: orange; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: orange; text-align: left;";
				$this->staff_id->CellCssStyle = "color: orange; text-align: left;";
				$this->staffid->CellCssStyle = "color: orange; text-align: left;";
				$this->report_by->CellCssStyle = "color: orange; text-align: left;";
				$this->departments->CellCssStyle = "color: orange; text-align: left;";
				$this->department->CellCssStyle = "color: orange; text-align: left;";
				$this->branch->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_id->CellCssStyle = "color: orange; text-align: left;";
				$this->category->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_type->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_location->CellCssStyle = "color: orange; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_category->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_description->CellCssStyle = "color: orange; text-align: left;";
				$this->sub_category->CellCssStyle = "color: orange; text-align: left;";
				$this->status->CellCssStyle = "color: orange; text-align: left;";
				$this->start_date->CellCssStyle = "color: orange; text-align: left;";
				$this->end_date->CellCssStyle = "color: orange; text-align: left;";
				$this->duration->CellCssStyle = "color: orange; text-align: left;";
			    $this->amount_paid->CellCssStyle = "color: orange; text-align: left;";
			    $this->last_updated_date->CellCssStyle = "color: orange; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: orange; text-align: left;";
				$this->operational_status->CellCssStyle = "color: orange; text-align: left;";
			}
			if ($this->status->CurrentValue == 2) {
				$this->id->CellCssStyle = "color: red; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: red; text-align: left;";
				$this->staff_id->CellCssStyle = "color: red; text-align: left;";
				$this->staffid->CellCssStyle = "color: red; text-align: left;";
				$this->report_by->CellCssStyle = "color: red; text-align: left;";
				$this->departments->CellCssStyle = "color: red; text-align: left;";
				$this->department->CellCssStyle = "color: red; text-align: left;";
				$this->branch->CellCssStyle = "color: red; text-align: left;";
				$this->incident_id->CellCssStyle = "color: red; text-align: left;";
				$this->incident_type->CellCssStyle = "color: red; text-align: left;";
				$this->incident_location->CellCssStyle = "color: red; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: red; text-align: left;";
				$this->category->CellCssStyle = "color: red; text-align: left;";
				$this->incident_category->CellCssStyle = "color: red; text-align: left;";
				$this->incident_description->CellCssStyle = "color: red; text-align: left;";
				$this->sub_category->CellCssStyle = "color: red; text-align: left;";
				$this->status->CellCssStyle = "color: red; text-align: left;";
				$this->start_date->CellCssStyle = "color: red; text-align: left;";
				$this->end_date->CellCssStyle = "color: red; text-align: left;";
				$this->duration->CellCssStyle = "color: red; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: red; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: red; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: red; text-align: left;";
				$this->operational_status->CellCssStyle = "color: red; text-align: left;";
			}
			if ($this->status->CurrentValue == 3) {
				$this->id->CellCssStyle = "color: blue; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: blue; text-align: left;";
				$this->staff_id->CellCssStyle = "color: blue; text-align: left;";
				$this->staffid->CellCssStyle = "color: blue; text-align: left;";
				$this->report_by->CellCssStyle = "color: blue; text-align: left;";
				$this->departments->CellCssStyle = "color: blue; text-align: left;";
				$this->department->CellCssStyle = "color: blue; text-align: left;";
				$this->branch->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_id->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_type->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_location->CellCssStyle = "color: blue; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: blue; text-align: left;";
				$this->category->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_category->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_description->CellCssStyle = "color: blue; text-align: left;";
				$this->sub_category->CellCssStyle = "color: blue; text-align: left;";
				$this->status->CellCssStyle = "color: blue; text-align: left;";
				$this->start_date->CellCssStyle = "color: blue; text-align: left;";
				$this->end_date->CellCssStyle = "color: blue; text-align: left;";
				$this->duration->CellCssStyle = "color: blue; text-align: left;";
			    $this->amount_paid->CellCssStyle = "color: blue; text-align: left;";
			    $this->last_updated_date->CellCssStyle = "color: blue; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: blue; text-align: left;";
				$this->operational_status->CellCssStyle = "color: blue; text-align: left;";
			}
			if ($this->status->CurrentValue == 0) {
				$this->id->CellCssStyle = "color: teal; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: teal; text-align: left;";
				$this->staff_id->CellCssStyle = "color: teal; text-align: left;";
				$this->staffid->CellCssStyle = "color: teal; text-align: left;";
				$this->report_by->CellCssStyle = "color: teal; text-align: left;";
				$this->departments->CellCssStyle = "color: teal; text-align: left;";
				$this->department->CellCssStyle = "color: teal; text-align: left;";
				$this->branch->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_id->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_type->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_location->CellCssStyle = "color: teal; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: teal; text-align: left;";
				$this->category->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_category->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_description->CellCssStyle = "color: teal; text-align: left;";
				$this->sub_category->CellCssStyle = "color: teal; text-align: left;";
				$this->status->CellCssStyle = "color: teal; text-align: left;";
				$this->start_date->CellCssStyle = "color: teal; text-align: left;";
				$this->end_date->CellCssStyle = "color: teal; text-align: left;";
				$this->duration->CellCssStyle = "color: teal; text-align: left;";
			    $this->amount_paid->CellCssStyle = "color: teal; text-align: left;";
			    $this->last_updated_date->CellCssStyle = "color: teal; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: teal; text-align: left;";
				$this->operational_status->CellCssStyle = "color: teal; text-align: left;";
			}
			if ($this->status->CurrentValue == 4) {
				$this->id->CellCssStyle = "color: purple; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: purple; text-align: left;";
				$this->staff_id->CellCssStyle = "color: purple; text-align: left;";
				$this->staffid->CellCssStyle = "color: purple; text-align: left;";
				$this->report_by->CellCssStyle = "color: purple; text-align: left;";
				$this->departments->CellCssStyle = "color: purple; text-align: left;";
				$this->department->CellCssStyle = "color: purple; text-align: left;";
				$this->branch->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_id->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_type->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_location->CellCssStyle = "color: purple; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: purple; text-align: left;";
				$this->category->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_description->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_category->CellCssStyle = "color: purple; text-align: left;";
				$this->sub_category->CellCssStyle = "color: purple; text-align: left;";
				$this->status->CellCssStyle = "color: purple; text-align: left;";
				$this->start_date->CellCssStyle = "color: purple; text-align: left;";
				$this->end_date->CellCssStyle = "color: purple; text-align: left;";
				$this->duration->CellCssStyle = "color: purple; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: purple; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: purple; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: purple; text-align: left;";
				$this->operational_status->CellCssStyle = "color: purple; text-align: left;";
			}
				if ($this->status->CurrentValue == 6) {
				$this->id->CellCssStyle = "color: coral; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: coral; text-align: left;";
				$this->staff_id->CellCssStyle = "color: coral; text-align: left;";
				$this->staffid->CellCssStyle = "color: coral; text-align: left;";
				$this->report_by->CellCssStyle = "color: coral; text-align: left;";
				$this->departments->CellCssStyle = "color: coral; text-align: left;";
				$this->department->CellCssStyle = "color: coral; text-align: left;";
				$this->branch->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_id->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_type->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_location->CellCssStyle = "color: coral; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: coral; text-align: left;";
				$this->category->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_description->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_category->CellCssStyle = "color: coral; text-align: left;";
				$this->sub_category->CellCssStyle = "color: coral; text-align: left;";
				$this->status->CellCssStyle = "color: coral; text-align: left;";
				$this->start_date->CellCssStyle = "color: coral; text-align: left;";
				$this->end_date->CellCssStyle = "color: coral; text-align: left;";
				$this->duration->CellCssStyle = "color: coral; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: coral; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: coral; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: coral; text-align: left;";
				$this->operational_status->CellCssStyle = "color: coral; text-align: left;";
			}
			if ($this->status->CurrentValue == 5) {
				$this->id->CellCssStyle = "color: green; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: green; text-align: left;";
				$this->staff_id->CellCssStyle = "color: green; text-align: left;";
				$this->staffid->CellCssStyle = "color: green; text-align: left;";
				$this->report_by->CellCssStyle = "color: green; text-align: left;";
				$this->departments->CellCssStyle = "color: green; text-align: left;";
				$this->department->CellCssStyle = "color: green; text-align: left;";
				$this->branch->CellCssStyle = "color: green; text-align: left;";
				$this->incident_id->CellCssStyle = "color: green; text-align: left;";
				$this->incident_type->CellCssStyle = "color: green; text-align: left;";
				$this->incident_location->CellCssStyle = "color: green; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: green; text-align: left;";
				$this->category->CellCssStyle = "color: green; text-align: left;";
				$this->incident_category->CellCssStyle = "color: green; text-align: left;";
				$this->incident_description->CellCssStyle = "color: green; text-align: left;";
				$this->sub_category->CellCssStyle = "color: green; text-align: left;";
				$this->status->CellCssStyle = "color: green; text-align: left;";
				$this->start_date->CellCssStyle = "color: green; text-align: left;";
				$this->end_date->CellCssStyle = "color: green; text-align: left;";
				$this->duration->CellCssStyle = "color: green; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: green; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: green; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: green; text-align: left;";
				$this->operational_status->CellCssStyle = "color: green; text-align: left;";
			}
			if ($this->status->CurrentValue == 7) {
				$this->id->CellCssStyle = "color: navy; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: navy; text-align: left;";
				$this->staff_id->CellCssStyle = "color: navy; text-align: left;";
				$this->staffid->CellCssStyle = "color: navy; text-align: left;";
				$this->report_by->CellCssStyle = "color: navy; text-align: left;";
				$this->departments->CellCssStyle = "color: navy; text-align: left;";
				$this->department->CellCssStyle = "color: navy; text-align: left;";
				$this->branch->CellCssStyle = "color: navy; text-align: left;";
				$this->incident_id->CellCssStyle = "color: navy; text-align: left;";
				$this->incident_type->CellCssStyle = "color: navy; text-align: left;";
				$this->incident_location->CellCssStyle = "color: navy; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: navy; text-align: left;";
				$this->category->CellCssStyle = "color: navy; text-align: left;";
				$this->incident_category->CellCssStyle = "color: navy; text-align: left;";
				$this->incident_description->CellCssStyle = "color: navy; text-align: left;";
				$this->sub_category->CellCssStyle = "color: navy; text-align: left;";
				$this->status->CellCssStyle = "color: navy; text-align: left;";
				$this->start_date->CellCssStyle = "color: navy; text-align: left;";
				$this->end_date->CellCssStyle = "color: navy; text-align: left;";
				$this->duration->CellCssStyle = "color: navy; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: navy; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: navy; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: navy; text-align: left;";
				$this->operational_status->CellCssStyle = "color: navy; text-align: left;";
			}
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
