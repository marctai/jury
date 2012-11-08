<?php

class DebatesModel extends Model
{
	public function getOpen()
	{
		// TODO: hämta rows där både for och agianst är null. 
		$sql = "SELECT id, subject, player_for
		    FROM debates 
		    WHERE player_for IS NULL 
		    AND player_against IS NOT NULL
		    OR player_against IS NULL
		    AND player_for IS NOT NULL";
		
		try {
		    $data = $this->pdo->query($sql);
		    if ($data->rowCount()) {
				$debates = array();
				foreach ($data as $row) {
					$debate = array();
					$debate['id'] = $row['id'];
					$debate['subject'] = $row['subject'];
					$debate['stance'] = $row['player_for'] == '' ? 'for' : 'against';
					$debates[] = $debate;
				}
			} else {
				$debates[] = 'No open debates';
			}
			return $debates;

		} catch (PDOException $e) {
			echo 'Database error: ' . $e->getMessage();
		}
	}

	// public function putOpen($id, $arg, $stance)
	// {

	// }
}