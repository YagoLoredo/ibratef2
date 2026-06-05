<?= formTitulo("Agendamento de Serviço") ?>

<div class="container mt-3">

    <form method="POST" action="<?= $this->request->formAction() ?>">

        <input type="hidden" name="id" value="<?= setValor("id") ?>">
        <input type="hidden" name="usuario_id" value="<?= $_SESSION['userId'] ?? '' ?>">

        <div class="row">

            <!-- Data -->
            <div class="col-md-4 mb-3">
                <label for="data" class="form-label">Data</label>
                <input type="date" class="form-control"
                       id="data"
                       name="data"
                       value="<?= setValor("data") ?>"
                       min="<?= date('Y-m-d') ?>"
                       required>
                <?= setMsgFilderError("data") ?>
            </div>

            <!-- Horário -->
            <div class="col-md-4 mb-3">
                <label for="horario" class="form-label">Horário</label>
                <select class="form-select" name="horario" id="horario" required>
                    <option value="">Selecione...</option>
                </select>
                <?= setMsgFilderError("horario") ?>
            </div>

            <!-- Tipo de Serviço -->
            <div class="col-md-4 mb-3">
                <label for="tipo_servico_id" class="form-label">Tipo de Serviço</label>
                <select class="form-select" name="tipo_servico_id" id="tipo_servico_id" required>
                    <option value="">Selecione...</option>

                    <?php foreach ($dados['aTipoServico'] as $value): ?>
                        <option value="<?= $value['id'] ?>" <?= setValor('tipo_servico_id') == $value['id'] ? "selected" : "" ?>>
                            <?= $value['nome'] ?>
                        </option>
                    <?php endforeach; ?>

                </select>
                <?= setMsgFilderError("tipo_servico_id") ?>
            </div>

            <!-- Observações -->
            <div class="col-md-12 mb-3">
                <label for="observacoes" class="form-label">Observações</label>
                <textarea class="form-control"
                          name="observacoes"
                          id="observacoes"
                          rows="3"
                          maxlength="255"
                          placeholder="Observações do agendamento"><?= setValor("observacoes") ?></textarea>
                <?= setMsgFilderError("observacoes") ?>
            </div>

        </div>

        <div class="text-end">
            <?= formButton() ?>
        </div>

    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const dataInput = document.getElementById('data');
    const horarioSelect = document.getElementById('horario');

    function gerarHorarios() {

        horarioSelect.innerHTML = '<option value="">Selecione...</option>';

        if (!dataInput.value) return;

        const dataSelecionada = new Date(dataInput.value + "T00:00:00");
        const hoje = new Date();

        const mesmaData =
            dataSelecionada.getFullYear() === hoje.getFullYear() &&
            dataSelecionada.getMonth() === hoje.getMonth() &&
            dataSelecionada.getDate() === hoje.getDate();

        const horaAtual = hoje.getHours();

        for (let hora = 8; hora <= 17; hora++) {

            // 🔥 bloqueia horários passados se for hoje
            if (mesmaData && hora <= horaAtual) continue;

            let h = String(hora).padStart(2, '0') + ":00";

            let option = document.createElement("option");
            option.value = h;
            option.textContent = h;

            horarioSelect.appendChild(option);
        }
    }

    dataInput.addEventListener('change', function () {

        const data = new Date(this.value + "T00:00:00");
        const dia = data.getDay();

        // 🚫 bloqueia fim de semana
        if (dia === 0 || dia === 6) {
            alert("Só é permitido agendar de segunda a sexta.");
            this.value = "";
            horarioSelect.innerHTML = '<option value="">Selecione...</option>';
            return;
        }

        gerarHorarios();
    });

    // 🔥 roda ao carregar a página
    gerarHorarios();
});
</script>