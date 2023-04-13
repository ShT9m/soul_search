{{-- report user modal --}}
<div class="modal fade" id="reportUserModal" tabindex="-1" aria-labelledby="reportUserModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-menu">
			<div class="modal-header">
				<h2 class="modal-title" id="reportUserModal">Report User</h2>
			</div>
			<div class="modal-body">
                <div class="mb-3">tell us the problem</div>
                <div>
                    <form action="#" method="post">
                        @csrf
                        <input type="hidden" value="{{ $user->id }}" name="user_id">

                        <div class="mb-2">
                            <input type="checkbox" class="form-check-input" id="comms" name="violation_types[]" value="communication">
                            <label for="comms" class="form-check-label fw-bold">Comms Abuse</label>
                            <div class="text-muted text-sm ms-3">Offensive Language, Hateful Speech, Sexual Harassment</div>
                        </div>

                        <div class="mb-2">
                            <input type="checkbox" class="form-check-input" id="names" name="violation_types[]" value="name">
                            <label for="names" class="form-check-label fw-bold">Offensive or Inappropriate Names</label>
                            <div class="text-muted text-sm ms-3">Username, Tag</div>
                        </div>

                        <div class="mb-3">
                            <input type="checkbox" class="form-check-input" id="threats" name="violation_types[]" value="threat">
                            <label for="threats" class="form-check-label fw-bold">Threats</label>
                            <div class="text-muted text-sm ms-3">Off-App Physical and Emotional Abuse, Doxxing, Bullying</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger btn-sm px-5">Send</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
