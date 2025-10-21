<div class="row">
    <div class="col-md-6">

        <div class="mb-3 row">
            <label for="company_name" class="col-sm-3 col-form-label">Nome da Empresa:<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', $company->company_name) }}" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_owner" class="col-sm-3 col-form-label">Dono da Empresa:</label>
            <div class="col-sm-9">
                <select id="company_owner" name="company_owner" class="form-select" required>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" @selected(old('company_owner', $company->company_owner) === $id)>{{ $name }} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_type" class="col-sm-3 col-form-label">Tipo:</label>
            <div class="col-sm-9">
                <select id="company_type" name="company_type" class="form-select">
                    @foreach($types as $id => $type)
                        <option value="{{ $id }}" @selected(old('company_type', $company->company_type) === $id)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3 h-100 d-flex flex-column">
            <label for="company_description" class="form-label">Descrição:</label>
            <textarea id="company_description" name="company_description" class="form-control flex-grow-1" rows="22">{{ old('company_description', $company->company_description) }}</textarea>
        </div>
    </div>
</div>
