<?php

class DebatesModel extends Model
{
	public function getOpen()
	{
		// First check if there is debates waiting for a second debater
		$sql = "SELECT debates.id, arguments.stance, subjects.subject, subjects.id AS subjects_id
			FROM debates
			JOIN arguments ON FK_arguments_id_first = arguments.id
			JOIN subjects ON FK_subjects_id = subjects.id
			WHERE FK_arguments_id_second IS NULL 
			LIMIT 0 , 30";
		
		try {
		    $data = $this->pdo->query($sql);
		    if ($data->rowCount() > 0) {
		    	$debates = array();
				foreach ($data as $row) {
					$debate = array('id' => $row['id'], 'subject' => $row['subject'], 'subject_id' => $row['subjects_id']);
					// The challenger takes opposite stance
					$debate['stance'] = $row['stance'] == 'for' ? 'against' : 'for';
					$debates[] = $debate;
				}
				return $debates;
			} else {
				return $this->startNewGame();
			}
		} catch (PDOException $e) {
			echo 'Database error: ' . $e->getMessage();
		}
	}

	protected function startNewGame()
	{
		// Select a subject, subject_id
		// TODO: Check to see if subject already been displayed 
		$sql = "SELECT id, subject FROM subjects ORDER BY RAND() LIMIT 0,1;";

		$debate = array();
		try {
			$data = $this->pdo->query($sql);
			if ($data->rowCount() == 1) {
				$row = $data->fetch(PDO::FETCH_ASSOC);
				$debate = array('subject' => $row['subject'], 'subject_id' => $row['id']);
			} else {
				$debate = array('message' => 'No open debates and no subjects for new debates. Bummer! :(');
				return $debate;
			}
		} catch (PDOException $e) {
			echo 'Database error: ' . $e->getMessage();
		}

		// Randomize stance
		$stance = array('for', 'against');
		$debate['stance'] = $stance[mt_rand(0, 1)];

		return $debate;
	}

	// public function putOpen($id, $arg, $stance)
	// {
	// 	$arg_stance = 'arg_' . $stance;
	// 	$stmt = $this->pdo->prepare("UPDATE debates SET arg_for = :arg WHERE id = :id");
	// 	$stmt->execute(array(
	// 		':arg' => $arg,
	// 		':id' => $id));

	// }

	// public function postOpen($stance, $argument, $subject_id)
	// {
	// 	// Insert into arguments
	// 	$stmt = $this->pdo->prepare("INSERT INTO arguments WHERE stance = :stance, argument = :argument, FK_subjects_id = :subject_id");
	// 	$params = array(':stance' => $stance, 
	// 		':argument' => $argument, 
	// 		':subject_id' => $subject_id);
	// 	$stmt->execute($params);

	// 	// Retrive arguments id
	// 	$last_inserted = PDO::lastInsertID();

	// 	// Insert id into debates
	// }
}