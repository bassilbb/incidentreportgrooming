<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(82, "mi_operational_status", $Language->MenuPhrase("82", "MenuText"), "operational_statuslist.php", -1, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}operational_status'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(17, "mi_home_php", $Language->MenuPhrase("17", "MenuText"), "home.php", -1, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}home.php'), FALSE, TRUE, "far fa-home");
$RootMenu->AddMenuItem(43, "mi_news", $Language->MenuPhrase("43", "MenuText"), "newslist.php", 17, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}news'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(47, "mi_testing_php", $Language->MenuPhrase("47", "MenuText"), "testing.php", 17, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}testing.php'), FALSE, TRUE, "fas fa-indent");
$RootMenu->AddMenuItem(33, "mci_Administrator", $Language->MenuPhrase("33", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "fas fa-user");
$RootMenu->AddMenuItem(1, "mi_branch", $Language->MenuPhrase("1", "MenuText"), "branchlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}branch'), FALSE, FALSE, "fas fa-transgender-alt");
$RootMenu->AddMenuItem(3, "mi_client", $Language->MenuPhrase("3", "MenuText"), "clientlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}client'), FALSE, FALSE, "fas fa-user");
$RootMenu->AddMenuItem(4, "mi_depertment", $Language->MenuPhrase("4", "MenuText"), "depertmentlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}depertment'), FALSE, FALSE, "fad fa-university");
$RootMenu->AddMenuItem(2, "mi_category", $Language->MenuPhrase("2", "MenuText"), "categorylist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}category'), FALSE, FALSE, "fas fa-braille");
$RootMenu->AddMenuItem(49, "mi_departments", $Language->MenuPhrase("49", "MenuText"), "departmentslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}departments'), FALSE, FALSE, "fad fa-university");
$RootMenu->AddMenuItem(10, "mi_sub_category", $Language->MenuPhrase("10", "MenuText"), "sub_categorylist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}sub-category'), FALSE, FALSE, "fas fa-braille");
$RootMenu->AddMenuItem(7, "mi_incident_category", $Language->MenuPhrase("7", "MenuText"), "incident_categorylist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}incident-category'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(5, "mi_gender", $Language->MenuPhrase("5", "MenuText"), "genderlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}gender'), FALSE, FALSE, "fas fa-venus-double");
$RootMenu->AddMenuItem(6, "mi_group", $Language->MenuPhrase("6", "MenuText"), "grouplist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}group'), FALSE, FALSE, "far fa-object-group");
$RootMenu->AddMenuItem(13, "mi_users", $Language->MenuPhrase("13", "MenuText"), "userslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}users'), FALSE, FALSE, "far fa-users");
$RootMenu->AddMenuItem(36, "mi_status", $Language->MenuPhrase("36", "MenuText"), "statuslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}status'), FALSE, FALSE, "fas fa-hourglass-start");
$RootMenu->AddMenuItem(37, "mi_incident_location", $Language->MenuPhrase("37", "MenuText"), "incident_locationlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}incident_location'), FALSE, FALSE, "fas fa-map-marker");
$RootMenu->AddMenuItem(38, "mi_no_of_people", $Language->MenuPhrase("38", "MenuText"), "no_of_peoplelist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}no_of_people'), FALSE, FALSE, "fas fa-user");
$RootMenu->AddMenuItem(39, "mi_type_of_incident", $Language->MenuPhrase("39", "MenuText"), "type_of_incidentlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}type_of_incident'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(48, "mi_audittrail", $Language->MenuPhrase("48", "MenuText"), "audittraillist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}audittrail'), FALSE, FALSE, "fas fa-braille");
$RootMenu->AddMenuItem(50, "mi_chart_report_php", $Language->MenuPhrase("50", "MenuText"), "chart_report.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}chart_report.php'), FALSE, TRUE, "");
$RootMenu->AddMenuItem(34, "mci_Incidents", $Language->MenuPhrase("34", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "fas fa-indent");
$RootMenu->AddMenuItem(8, "mi_report_form", $Language->MenuPhrase("8", "MenuText"), "report_formlist.php", 34, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}report_form'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(51, "mi_history", $Language->MenuPhrase("51", "MenuText"), "historylist.php", 34, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}history'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(81, "mci_Report_Snapshot", $Language->MenuPhrase("81", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fas fa-indent");
$RootMenu->AddMenuItem(52, "mi_daily_issue", $Language->MenuPhrase("52", "MenuText"), "daily_issuelist.php", 81, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}daily_issue'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(54, "mi_awaits_resolution", $Language->MenuPhrase("54", "MenuText"), "awaits_resolutionlist.php", 81, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}awaits_resolution'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(53, "mi_resolved_issue", $Language->MenuPhrase("53", "MenuText"), "resolved_issuelist.php", 81, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}resolved_issue'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(31, "mci_Settings", $Language->MenuPhrase("31", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "fas fa-cogs");
$RootMenu->AddMenuItem(44, "mi_userlevelpermissions", $Language->MenuPhrase("44", "MenuText"), "userlevelpermissionslist.php", 31, "", IsAdmin(), FALSE, FALSE, "fa fa-wrench");
$RootMenu->AddMenuItem(45, "mi_userlevels", $Language->MenuPhrase("45", "MenuText"), "userlevelslist.php", 31, "", IsAdmin(), FALSE, FALSE, "fa fa-cog");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
