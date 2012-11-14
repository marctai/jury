<?php

class DebatesModel extends Model
{
	private $hours_of_voting = 24;

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
		// catchen behövs nog inte här, den fångas upp av catchen i getOpen.
		} catch (PDOException $e) {
			echo 'Database error: ' . $e->getMessage();
		}

		// Randomize stance
		$stance = array('for', 'against');
		$debate['stance'] = $stance[mt_rand(0, 1)];

		return $debate;
	}

	public function putOpen($subject_id, $stance, $argument, $id)
	{
		return $this->insertArgument($subject_id, $stance, $argument, $id);
	}

	public function postOpen($subject_id, $stance, $argument)
	{
		return $this->insertArgument($subject_id, $stance, $argument);
	}

	protected function insertArgument($subject_id, $stance, $argument, $id = null)
	{
		try {
			$stmt = $this->pdo->prepare("INSERT INTO arguments SET stance = :stance, argument = :argument, FK_subjects_id = :subject_id");
			$params = array(':stance' => $stance, 
				':argument' => $argument, 
				':subject_id' => $subject_id);
			$stmt->execute($params);

			// Retrive arguments id
			$last_inserted_id = $this->pdo->lastInsertID();

			// If id is null create a new debate, else insert into existing debate
			if ($id == null) {
				$this->pdo->query("INSERT INTO debates SET FK_arguments_id_first = $last_inserted_id");
			} else {
				$this->pdo->query("UPDATE debates SET FK_arguments_id_second = $last_inserted_id WHERE id = $id");
			}
		} catch (PDOException $e) {
			echo 'Database error in posetOpen: ' . $e->getMessage();
		}
		return true;
	}

	public function getPending()
	{
		$sql = "SELECT debates.id, a1.stance AS stance_first, a1.argument AS argument_first, a2.stance AS stance_second, a2.argument AS argument_second
			FROM debates
			JOIN arguments a1 ON FK_arguments_id_first = a1.id
			JOIN arguments a2 ON FK_arguments_id_second = a2.id
			WHERE TIMESTAMPDIFF( HOUR , stamp, NOW( ) ) < {$this->hours_of_voting}
			LIMIT 0 , 30";

		try {
			$data = $this->pdo->query($sql);
			$debates = array();
			if ($data->rowCount() > 0) {
				foreach ($data as $row) {
					$debate = array('id' => $row['id'], 'first_argument' => array('stance' => $row['stance_first'], 'argument' => $row['argument_first']),
						'opposing_argument' => array('stance' => $row['stance_second'], 'argument' => $row['argument_second']));
					$debates[] = $debate;
				}
			} else {
				$debates['message'] = "No voting goin' on here mate.";
			}
		} catch (PDOException $e) {
			echo 'Database error in getPending: ' . $e->getMessage();
		}
		return $debates;
	}
}