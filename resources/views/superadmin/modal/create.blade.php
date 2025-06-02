<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addDBModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addDBModal">Create Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('addUser') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="namaDB" class="col-form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="namaDB" class="col-form-label">Password</label>
                        <input type="password"
                            class="form-control {{ session('show_modal') && $errors->has('password') ? 'is-invalid' : '' }}"
                            name="password" id="passUser" required>
                    </div>

                    {{-- Error per field --}}
                    <div class="invalid-feedback d-block {{ session('show_modal') && $errors->has('password') ? '' : 'd-none' }}"
                        id="password-error">
                        {{ $errors->first('password') }}
                    </div>

                    <div class="mb-3">
                        <label for="roleDropdown" class="form-label">Role</label>
                        <div class="form-floating">
                            <select class="form-select" name="role" id="roleDropdown" required>
                                <option selected value="" disabled>--Select Role--</option>
                                <option name="role" value="operator">Operator</option>
                                <option name="role" value="checker">Checker</option>
                                <option name="role" value="supervisor">Supervisor</option>
                            </select>
                        </div>
                    </div>
                    @if (strtolower(Auth::user()->username) == 'superadmin1')
                        <input type="hidden" name="statusApproval" value=0>
                    @elseif (strtolower(Auth::user()->username) == 'superadmin2')
                        <input type="hidden" name="statusApproval" value=1>
                    @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>
