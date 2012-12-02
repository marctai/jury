<?php

class PendingModel extends Model
{
	private $hours_of_voting = 24; // How long a debate will be up for voting

	public function getPendingDebates()
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

	public function vote($id, $stance)
	{
		$sql = "INSERT INTO votes
			SET FK_debates_id = $id,
			`$stance` = `$stance`+1 ON DUPLICATE KEY UPDATE `$stance` = `$stance`+1";

		try 
		{
			$this->pdo->query($sql);
		} 
		catch (PDOException $e) 
		{
			echo 'Database error in putPending: ' . $e->getMessage();
			die();
		}

		return true;
	}
}