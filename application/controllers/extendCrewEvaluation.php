<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExtendCrewEvaluation extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}
	
    function getDataPage($idPerson, $personName, $rank, $vessel, $coName, $ceName, $masterName)
	{
		$dataContext = new DataContext();

		$dataOut = array();
		$dataOut['optVessel'] = $dataContext->getVesselByOption("", "name");
		$dataOut['optRank'] = $dataContext->getRankByOption("", "name");

		$dataOut['idperson']     = base64_decode($idPerson);
		$dataOut['personName']   = base64_decode($personName);
		$dataOut['rank']         = base64_decode($rank);
		$dataOut['vessel']       = base64_decode($vessel);
		$dataOut['coName']       = base64_decode($coName);
		$dataOut['ceName']       = base64_decode($ceName);
		$dataOut['masterName']   = base64_decode($masterName);

		$this->load->view('frontend/extendCrewEvaluation', $dataOut);
	}

    function saveDataCrewEvaluation() {
		$data = $_POST;
		$dataIns = array();
		$criteriaData = array();
		$stData = "";
		$idPerson = base64_decode(base64_decode(base64_decode($data['txtIdPerson'])));
		$userDateTimeNow = $this->session->userdata('userCrewSystem') . "/" . date('Ymd') . "/" . date('H:i:s');
 		$txtIdEditCrew = isset($data['txtIdEditCrew']) ? $data['txtIdEditCrew'] : ''; 
		try {
			
			$dataIns['vessel'] = isset($data['txtVessel']) ? $data['txtVessel'] : '';
			$dataIns['seafarer_name'] = isset($data['txtSeafarerName']) ? $data['txtSeafarerName'] : '';
			$dataIns['rank'] = isset($data['txtRank']) ? $data['txtRank'] : '';
			$dataIns['date_of_report'] = (!empty($data['txtDateOfReport']) && $data['txtDateOfReport'] != "0000-00-00") ? $data['txtDateOfReport'] : null;
			$dataIns['reporting_period_from'] = (!empty($data['txtDateReportingPeriodFrom']) && $data['txtDateReportingPeriodFrom'] != "0000-00-00") ? $data['txtDateReportingPeriodFrom'] : null;
			$dataIns['reporting_period_to'] = (!empty($data['txtDateReportingPeriodTo']) && $data['txtDateReportingPeriodTo'] != "0000-00-00") ? $data['txtDateReportingPeriodTo'] : null;
			$dataIns['idperson'] = $idPerson;
			
			$dataIns['reason_midway_contract'] = isset($data['reasonMidway']) ? $data['reasonMidway'] : '';
			$dataIns['reason_signing_off'] = isset($data['reasonSigningOff']) ? $data['reasonSigningOff'] : '';
			$dataIns['reason_leaving_vessel'] = isset($data['reasonLeaving']) ? $data['reasonLeaving'] : '';
			$dataIns['reason_special_request'] = isset($data['reasonSpecialRequest']) ? $data['reasonSpecialRequest'] : '';

			$dataIns['master_comments'] = isset($data['txtMasterComments']) ? $data['txtMasterComments'] : '';
			$dataIns['reporting_officer_comments'] = isset($data['txtOfficerComments']) ? $data['txtOfficerComments'] : '';
			$dataIns['promote'] = isset($data['txtPromoted']) ? $data['txtPromoted'] : 'N';
			$dataIns['re_employ'] = isset($data['txtReemploy']) ? $data['txtReemploy'] : 'N';
			$dataIns['reporting_officer_name'] = isset($data['txtfullname']) ? $data['txtfullname'] : '';
			$dataIns['reporting_officer_rank'] = isset($data['slcRank']) ? $data['slcRank'] : '';
			$dataIns{'mastercoofullname'} = isset($data['txtmastercoofullname']) ? $data['txtmastercoofullname'] : '';
			$dataIns['received_by_cm'] = isset($data['txtreceived']) ? $data['txtreceived'] : '';
			$dataIns['date_of_receipt'] = (!empty($data['txtDateReceipt']) && $data['txtDateReceipt'] != "0000-00-00") ? $data['txtDateReceipt'] : null;
			$data['addUsrDate'] = $userDateTimeNow;

			if (empty($txtIdEditCrew)) {
				$insertId = $this->MCrewscv->insData("crew_evaluation_report", $dataIns);
				$mode = "insert";
				$id = $insertId;
			} 

			$criteriaList = array(
				"Ability/Knowledge of Job" => "ability",
				"Safety Consciousness" => "safety",
				"Dependability & Integrity" => "integrity",
				"Initiative" => "initiative",
				"Conduct" => "conduct",
				"Ability to get on with others" => "abilityGetOn",
				"Appearance (+ uniforms)" => "appearance",
				"Sobriety" => "sobriety",
				"English Language" => "english",
				"Leadership (Officers)" => "leadership"
			);

			foreach ($criteriaList as $criteriaName => $criteriaId) {
				$value = isset($data[$criteriaId]) ? $data[$criteriaId] : '';
				$criteriaData = array(
					'idperson' => $idPerson,
					'criteria_name' => $criteriaName,
					'excellent' => ($value == '4') ? 'Y' : '',
					'good' => ($value == '3') ? 'Y' : '',
					'fair' => ($value == '2') ? 'Y' : '',
					'poor' => ($value == '1') ? 'Y' : '',
					'identify' => isset($data["txtIdentify" . ucfirst($criteriaId)]) ? $data["txtIdentify" . ucfirst($criteriaId)] : '',
					'addUsrDate' => $userDateTimeNow
				);

				$this->MCrewscv->insData("crew_evaluation_criteria", $criteriaData);
			}

			$stData = array(
				"status" => "success",
				"message" => "Save Success..!!",
				"mode" => $mode,
				"id" => $id
			);
		} catch (\Throwable $th) {
			$stData = array(
				"status" => "error",
				"message" => "Error: " . $th->getMessage()
			);
		}

		echo json_encode($stData);
	}

	function generateLink()
	{
		$data = $_POST;
		$idPerson = $data['txtModalGenLinkIdPerson'];
		$department = $data['department'];

		$sql = "SELECT 
			A.idperson,
			TRIM(CONCAT(D.fname, ' ', COALESCE(D.mname, ''), ' ', D.lname)) AS fullName,
			B.nmrank,
			C.nmvsl,

			(SELECT TRIM(CONCAT(MP.fname, ' ', COALESCE(MP.mname, ''), ' ', MP.lname))
			FROM tblcontract TC
			JOIN mstpersonal MP ON MP.idperson = TC.idperson
			WHERE TC.signonvsl = A.signonvsl 
			AND TC.signonrank = '044' 
			AND TC.signoffdt = '0000-00-00'
			LIMIT 1) AS co_name,

			(SELECT TRIM(CONCAT(MP.fname, ' ', COALESCE(MP.mname, ''), ' ', MP.lname))
			FROM tblcontract TC
			JOIN mstpersonal MP ON MP.idperson = TC.idperson
			WHERE TC.signonvsl = A.signonvsl 
			AND TC.signonrank = '041' 
			AND TC.signoffdt = '0000-00-00'
			LIMIT 1) AS ce_name,

			(SELECT TRIM(CONCAT(MP.fname, ' ', COALESCE(MP.mname, ''), ' ', MP.lname))
			FROM tblcontract TC
			JOIN mstpersonal MP ON MP.idperson = TC.idperson
			WHERE TC.signonvsl = A.signonvsl 
			AND TC.signonrank = '037' 
			AND TC.signoffdt = '0000-00-00'
			LIMIT 1) AS mastername

		FROM tblcontract A
		LEFT JOIN mstrank B ON B.kdrank = A.signonrank
		LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
		LEFT JOIN mstpersonal D ON D.idperson = A.idperson
		WHERE A.signoffdt = '0000-00-00' 
		AND A.idperson = '".$idPerson."'";

		$crewData = $this->MCrewscv->getDataQuery($sql);

		$personName = $crewData[0]->fullName;
		$rank = $crewData[0]->nmrank;
		$vessel = $crewData[0]->nmvsl;
		$coName = $crewData[0]->co_name;
		$ceName = $crewData[0]->ce_name;
		$masterName = $crewData[0]->mastername;

		$encoded = array(
			'idperson'    => base64_encode($idPerson),
			'personName'  => base64_encode($personName),
			'rank'        => base64_encode($rank),
			'vessel'      => base64_encode($vessel),
			'coName'      => base64_encode($coName),
			'ceName'      => base64_encode($ceName),
			'masterName'  => base64_encode($masterName),
		);

		echo json_encode(array(
			'url' => base_url("extendCrewEvaluation/getDataPage/" .
				$encoded['idperson'] . '/' .
				$encoded['personName'] . '/' .
				$encoded['rank'] . '/' .
				$encoded['vessel'] . '/' .
				$encoded['coName'] . '/' .
				$encoded['ceName'] . '/' .
				$encoded['masterName']
			)
		));
	}
	
	function printCrewEvaluation($encryptedId = "")
	{
		$dataOut = array();

		$decryptedId = base64_decode(base64_decode(base64_decode(urldecode($encryptedId))));

		$sqlReport = "SELECT * FROM crew_evaluation_report WHERE id = '".$id."'";
		$reportData = $this->MCrewscv->getDataQuery($sqlReport);

		$row = (object)$reportData[0]; 
		$idPerson = $row->idperson;

		$sqlCriteria = "SELECT * FROM crew_evaluation_criteria
			WHERE deletests = '0' AND idperson = '".$idPerson."' ORDER BY id ASC";
		$criteriaData = $this->MCrewscv->getDataQuery($sqlCriteria);

		$vessel = '';
		$reEmploy = '';
		$seafarerName = '';
		$rank = '';
		$dateOfReport = '';
		$reportPeriodFrom = '';
		$reportPeriodTo = '';
		$masterComments = '';
		$reportingOfficerComments = '';
		$promote = '';
		$reportingOfficerName = '';
		$reportingOfficerRank = '';
		$receivedByCM = '';
		$dateOfReceipt = '';
		$mastercoofullname = '';
		
		$reasonMidway = '';
		$reasonLeaving = '';
		$reasonSigningOff = '';
		$reasonSpecial = '';
		
		function getChecked($value) {
			return ($value === 'Y') ? '&#10004;' : '';
		}

		if (count($reportData) > 0) {
			$row = $reportData[0];
		
			$idPerson = $row->idperson;
			$id = $row->id;
			$vessel = $row->vessel;
			$seafarerName = $row->seafarer_name;
			$rank = $row->rank;
			$dateOfReport = date('d/m/Y',strtotime($row->date_of_report));
			$reportPeriodFrom = date('d/m/Y', strtotime($row->reporting_period_from));
			$reportPeriodTo = date('d/m/Y', strtotime($row->reporting_period_to));
			$masterComments = $row->master_comments;
			$reportingOfficerComments = $row->reporting_officer_comments;
			$promote = $row->promote;
			$reportingOfficerName = $row->reporting_officer_name;
			$reportingOfficerRank = $row->reporting_officer_rank;
			$mastercoofullname = $row->mastercoofullname;
			$receivedByCM = $row->received_by_cm;
			$dateOfReceipt = date('d/m/Y', strtotime($row->date_of_receipt));
			$reEmploy = $row->re_employ; 
			
			$reasonMidway = getChecked($row->reason_midway_contract);
			$reasonLeaving = getChecked($row->reason_leaving_vessel);
			$reasonSigningOff = getChecked($row->reason_signing_off);
			$reasonSpecial = getChecked($row->reason_special_request);
		

		$criteriaTable = '';
		if (!empty($criteriaData)) {
			foreach ($criteriaData as $cRow) {
				$criteriaTable .= '<tr>';
				$criteriaTable .= '<td>'.htmlspecialchars($cRow->criteria_name).'</td>';
				$criteriaTable .= '<td style="text-align:center;">'.getChecked($cRow->excellent).'</td>';
				$criteriaTable .= '<td style="text-align:center;">'.getChecked($cRow->good).'</td>';
				$criteriaTable .= '<td style="text-align:center;">'.getChecked($cRow->fair).'</td>';
				$criteriaTable .= '<td style="text-align:center;">'.getChecked($cRow->poor).'</td>';
				$criteriaTable .= '<td style="text-align:center;">'.htmlspecialchars($cRow->identify).'</td>';
				$criteriaTable .= '</tr>';
			}
		}

		$dataOut = array(
			'vessel' => $row->vessel,
			'seafarerName' => $row->seafarer_name,
			'rank' => $row->rank,
			'dateOfReport' => date('d/m/Y', strtotime($row->date_of_report)),
			'reportPeriodFrom' => date('d/m/Y', strtotime($row->reporting_period_from)),
			'reportPeriodTo' => date('d/m/Y', strtotime($row->reporting_period_to)),
			'reasonMidway' => getChecked($row->reason_midway_contract),
			'reasonLeaving' => getChecked($row->reason_leaving_vessel),
			'reasonSigningOff' => getChecked($row->reason_signing_off),
			'reasonSpecial' => getChecked($row->reason_special_request),
			'criteriaTable' => $criteriaTable,
			'masterComments' => $row->master_comments,
			'reportingOfficerComments' => $row->reporting_officer_comments,
			'promote' => $row->promote,
			'reportingOfficerName' => $row->reporting_officer_name,
			'reportingOfficerRank' => $row->reporting_officer_rank,
			'mastercoofullname' => $row->mastercoofullname,
			'receivedByCM' => $row->received_by_cm,
			'dateOfReceipt' => date('d/m/Y', strtotime($row->date_of_receipt)),
			'reEmploy' => $row->re_employ
		);

		$this->load->view('frontend/reportCrewEvaluation', $dataOut);
	}
}