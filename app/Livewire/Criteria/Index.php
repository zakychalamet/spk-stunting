<?php

namespace App\Livewire\Criteria;

use App\Models\Criterion;
use App\Models\CriterionScale;
use Livewire\Component;

class Index extends Component
{
    public bool $showCriterionModal = false;
    public ?int $editingCriterionId = null;
    public string $criterionCode = '';
    public string $criterionName = '';
    public string $criterionType = 'benefit';
    public string $criterionDescription = '';

    public bool $showScaleModal = false;
    public ?int $selectedCriterionId = null;
    public ?int $editingScaleId = null;
    public string $scaleParameter = '';
    public int $scaleScore = 1;
    public string $scaleLabel = '';
    public string $scaleCategory = 'Normal';
    public ?float $scaleMinValue = null;
    public ?float $scaleMaxValue = null;

    public function openAddCriterionModal()
    {
        $this->editingCriterionId = null;
        $nextNum = Criterion::count() + 1;
        $this->criterionCode = 'K' . $nextNum;
        $this->criterionName = '';
        $this->criterionType = 'benefit';
        $this->criterionDescription = '';
        $this->showCriterionModal = true;
    }

    public function editCriterion(int $id)
    {
        $criterion = Criterion::findOrFail($id);
        $this->editingCriterionId = $criterion->id;
        $this->criterionCode = $criterion->code;
        $this->criterionName = $criterion->name;
        $this->criterionType = $criterion->type;
        $this->criterionDescription = $criterion->description ?? '';
        $this->showCriterionModal = true;
    }

    public function saveCriterion()
    {
        $rules = [
            'criterionCode' => 'required|string|max:10|unique:criteria,code,' . ($this->editingCriterionId ?? 'NULL') . ',id',
            'criterionName' => 'required|string|max:100',
            'criterionType' => 'required|in:benefit,cost',
        ];

        $messages = [
            'criterionCode.required' => 'Kode kriteria wajib diisi.',
            'criterionCode.unique' => 'Kode kriteria sudah digunakan.',
            'criterionName.required' => 'Nama kriteria wajib diisi.',
        ];

        $this->validate($rules, $messages);

        if ($this->editingCriterionId) {
            $criterion = Criterion::findOrFail($this->editingCriterionId);
            $criterion->update([
                'code' => strtoupper($this->criterionCode),
                'name' => $this->criterionName,
                'type' => $this->criterionType,
                'description' => $this->criterionDescription,
            ]);
            session()->flash('success', "Kriteria {$criterion->name} berhasil diperbarui.");
        } else {
            $criterion = Criterion::create([
                'code' => strtoupper($this->criterionCode),
                'name' => $this->criterionName,
                'type' => $this->criterionType,
                'description' => $this->criterionDescription,
            ]);
            session()->flash('success', "Kriteria {$criterion->name} berhasil ditambahkan.");
        }

        $this->showCriterionModal = false;
    }

    public function deleteCriterion(int $id)
    {
        $criterion = Criterion::findOrFail($id);
        $criterionName = $criterion->name;
        $criterion->scales()->delete();
        $criterion->delete();
        session()->flash('success', "Kriteria {$criterionName} berhasil dihapus.");
    }

    public function openAddScaleModal(int $criterionId)
    {
        $this->selectedCriterionId = $criterionId;
        $this->reset(['editingScaleId', 'scaleParameter', 'scaleScore', 'scaleLabel', 'scaleCategory', 'scaleMinValue', 'scaleMaxValue']);
        $this->scaleScore = 1;
        $this->scaleCategory = 'Normal';
        $this->showScaleModal = true;
    }

    public function editScale(int $scaleId)
    {
        $scale = CriterionScale::findOrFail($scaleId);
        $this->editingScaleId = $scale->id;
        $this->selectedCriterionId = $scale->criterion_id;
        $this->scaleParameter = $scale->parameter;
        $this->scaleScore = $scale->score;
        $this->scaleLabel = $scale->label;
        $this->scaleCategory = $scale->category;
        $this->scaleMinValue = $scale->min_value;
        $this->scaleMaxValue = $scale->max_value;
        $this->showScaleModal = true;
    }

    public function saveScale()
    {
        $this->validate([
            'scaleParameter' => 'required|string|max:100',
            'scaleScore' => 'required|integer|min:1|max:9',
            'scaleLabel' => 'required|string|max:150',
            'scaleCategory' => 'required|string|max:50',
        ], [
            'scaleParameter.required' => 'Parameter penilaian wajib diisi.',
            'scaleScore.required' => 'Skor wajib diisi.',
            'scaleLabel.required' => 'Keterangan skala wajib diisi.',
        ]);

        if ($this->editingScaleId) {
            $scale = CriterionScale::findOrFail($this->editingScaleId);
            $scale->update([
                'parameter' => $this->scaleParameter,
                'score' => $this->scaleScore,
                'label' => $this->scaleLabel,
                'category' => $this->scaleCategory,
                'min_value' => $this->scaleMinValue,
                'max_value' => $this->scaleMaxValue,
            ]);
            session()->flash('success', 'Skala penilaian berhasil diperbarui.');
        } else {
            CriterionScale::create([
                'criterion_id' => $this->selectedCriterionId,
                'parameter' => $this->scaleParameter,
                'score' => $this->scaleScore,
                'label' => $this->scaleLabel,
                'category' => $this->scaleCategory,
                'min_value' => $this->scaleMinValue,
                'max_value' => $this->scaleMaxValue,
            ]);
            session()->flash('success', 'Skala penilaian baru berhasil ditambahkan.');
        }

        $this->showScaleModal = false;
    }

    public function deleteScale(int $scaleId)
    {
        $scale = CriterionScale::findOrFail($scaleId);
        $scale->delete();
        session()->flash('success', 'Skala penilaian berhasil dihapus.');
    }

    public function render()
    {
        $criteria = Criterion::with('scales')->orderBy('code')->get();

        return view('livewire.criteria.index', [
            'criteria' => $criteria,
        ])->layout('layouts.app', [
            'title' => 'Kriteria dan Skala Penilaian',
            'breadcrumb' => 'Kriteria & Skala Penilaian',
        ]);
    }
}
