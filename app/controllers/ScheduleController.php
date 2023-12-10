<?php 	

	class ScheduleController extends Controller
	{

		public function __construct()
		{
			$this->user = model('UserModel');
			$this->schedule = model('ScheduleModel');
		}

		public function create()
		{
			$userId = request()->get('user_id');

			$data = [
				'daysoftheweek' => dayOfWeeks(),
				'userId' => $userId,
				'user'   => $this->user->get($userId)
			];
			
			return $this->view('schedule/create' , $data);
		}

		public function store()
		{
			$post = request()->posts();
			
			$response = $this->schedule->newSchedule([
				'user_id' => $post['user_id'],
				'schedules' => $post['day']
			]);

			if(!$response) {
				Flash::set( $this->schedule->getErrorString() , 'danger');
			}

			return redirect('User/edit/'.$post['user_id']);
		}

		public function edit($id)
		{
			$schedule = $this->schedule->get($id);

			$user = $this->user->get($schedule->user_id);

			$data = [
				'schedule' => $schedule,
				'user'     => $user
			];

			return $this->view('schedule/edit', $data);
		}

		public function update()
		{
			$post = request()->posts();

			$response = $this->schedule->update([
				'time_in' => $post['time_in'],
				'time_out' => $post['time_out'],
				'is_off'   => $post['is_off']
			] , $post['id']);

			if($response) {
				Flash::set("Schedule Updated");
			}

			return request()->return();
		}
	}