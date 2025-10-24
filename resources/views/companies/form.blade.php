@csrf
<div class="row">
    <div class="col-md-6">

        <h5 class="mb-3">Informações Principais</h5>

        <div class="mb-3 row">
            <label for="company_name" class="col-sm-3 col-form-label">Nome:<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" id="company_name" name="company_name" class="form-control"
                       value="{{ old('company_name', $company->company_name ?? '') }}" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_owner" class="col-sm-3 col-form-label">Dono:<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <select id="company_owner" name="company_owner" class="form-select" required>
                    <option value="">Selecione...</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" @selected((old('company_owner', $company->company_owner ?? null) === $id))>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_type" class="col-sm-3 col-form-label">Tipo:</label>
            <div class="col-sm-9">
                <select id="company_type" name="company_type" class="form-select">
                    <option value="">Selecione...</option>
                    @foreach($types as $idType => $typeName)
                        <option value="{{ $idType }}" @selected((old('company_type', $company->company_type ?? null) === $idType))>
                            {{ $typeName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr class="my-4">
        <h5 class="mb-3">Contato</h5>

        <div class="mb-3 row">
            <label for="company_email" class="col-sm-3 col-form-label">E-mail:</label>
            <div class="col-sm-9">
                <input type="email" id="company_email" name="company_email" class="form-control"
                       value="{{ old('company_email', $company->company_email ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_phone1" class="col-sm-3 col-form-label">Telefone:</label>
            <div class="col-sm-9">
                <input type="text" id="company_phone1" name="company_phone1" class="form-control"
                       value="{{ old('company_phone1', $company->company_phone1 ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_fax" class="col-sm-3 col-form-label">Fax:</label>
            <div class="col-sm-9">
                <input type="text" id="company_fax" name="company_fax" class="form-control"
                       value="{{ old('company_fax', $company->company_fax ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_primary_url" class="col-sm-3 col-form-label">URL:</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" id="company_primary_url" name="company_primary_url" class="form-control"
                           value="{{ old('company_primary_url', $company->company_primary_url ?? '') }}">
                </div>
            </div>
        </div>

        <hr class="my-4">
        <h5 class="mb-3">Endereço</h5>

        <div class="mb-3 row">
            <label for="company_address1" class="col-sm-3 col-form-label">Endereço:</label>
            <div class="col-sm-9">
                <input type="text" id="company_address1" name="company_address1" class="form-control"
                       value="{{ old('company_address1', $company->company_address1 ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_city" class="col-sm-3 col-form-label">Cidade:</label>
            <div class="col-sm-9">
                <input type="text" id="company_city" name="company_city" class="form-control"
                       value="{{ old('company_city', $company->company_city ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_state" class="col-sm-3 col-form-label">Estado:</label>
            <div class="col-sm-9">
                <input type="text" id="company_state" name="company_state" class="form-control"
                       value="{{ old('company_state', $company->company_state ?? '') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="company_zip" class="col-sm-3 col-form-label">CEP:</label>
            <div class="col-sm-9">
                <input type="text" id="company_zip" name="company_zip" class="form-control"
                       value="{{ old('company_zip', $company->company_zip ?? '') }}">
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <h5 class="mb-3">Descrição</h5>

        <div class="mb-3 h-80 d-flex flex-column">
            <textarea id="company_description" name="company_description" class="form-control flex-grow-1"
                      rows="20">{{ old('company_description', $company->company_description ?? '') }}</textarea>
        </div>
    </div>
</div>
