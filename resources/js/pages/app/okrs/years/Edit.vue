<script setup lang="ts">
import { ref } from 'vue';
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Spinner } from '@/components/ui/spinner';
import { Button } from '@/components/ui/button';
import DeleteConfirmationDialog from '@/components/custom/DeleteConfirmation.vue';
import okrsRoutes from '@/routes/okrs';
import yearsRoutes from '@/routes/years';
import quartersRoutes from '@/routes/quarters';

interface Quarter {
    id: number;
    label: string;
    start_date: string;
    end_date: string;
}

interface Year {
    id: number;
    year: number;
    quarters: Quarter[];
}

interface Props {
    year: Year;
}

const props = defineProps<Props>();

// Individual quarter form
const showQuarterForm = ref(false);
const quarterForm = ref({
    label: '',
    start_date: '',
    end_date: '',
});

// Bulk quarter creation
const showBulkForm = ref(false);
const quartersData = ref([
    { label: 'Q1', start_date: `${props.year.year}-01-01`, end_date: `${props.year.year}-03-31` },
    { label: 'Q2', start_date: `${props.year.year}-04-01`, end_date: `${props.year.year}-06-30` },
    { label: 'Q3', start_date: `${props.year.year}-07-01`, end_date: `${props.year.year}-09-30` },
    { label: 'Q4', start_date: `${props.year.year}-10-01`, end_date: `${props.year.year}-12-31` },
]);

// Editing quarter
const editingQuarter = ref<Quarter | null>(null);

const submitQuarter = () => {
    router.post(quartersRoutes.store(props.year.year).url, quarterForm.value, {
        preserveScroll: true,
        onSuccess: () => {
            showQuarterForm.value = false;
            quarterForm.value = { label: '', start_date: '', end_date: '' };
        },
    });
};

const submitBulkQuarters = () => {
    router.post(quartersRoutes.store.bulk(props.year.year).url, { quarters: quartersData.value }, {
        preserveScroll: true,
        onSuccess: () => {
            showBulkForm.value = false;
        },
    });
};

const updateQuarter = () => {
    if (!editingQuarter.value) return;
    
    router.put(
        quartersRoutes.update({ year: props.year.year, quarter: editingQuarter.value.id }).url,
        { label: editingQuarter.value.label, start_date: editingQuarter.value.start_date, end_date: editingQuarter.value.end_date },
        {
            preserveScroll: true,
            onSuccess: () => {
                editingQuarter.value = null;
            },
        }
    );
};

const deleteQuarter = (quarter: Quarter) => {
    router.delete(quartersRoutes.destroy({ year: props.year.year, quarter: quarter.id }).url, {
        preserveScroll: true,
    });
};

const getQuarterDates = (quarter: Quarter) => {
    const start = new Date(quarter.start_date).toLocaleDateString();
    const end = new Date(quarter.end_date).toLocaleDateString();
    return `${start} - ${end}`;
};
</script>

<template>
    <Head title="Edit year" />

    <div class="app-container space-y-8">
        <!-- Edit Year Form -->
        <div class="form">
            <div class="header">
                <Link :href="okrsRoutes.index()">&larr;</Link>
                <h2 class="title">Edit Year</h2>
            </div>

            <Form :action="yearsRoutes.update(props.year.year).url" method="put" v-slot="{ errors, processing }">
                <div class="inputs-group">
                    <Label for="year">Year</Label>
                    <Input
                        id="year"
                        type="number"
                        name="year"
                        autofocus
                        :tabindex="1"
                        autocomplete="year"
                        placeholder="Year"
                        :default-value="props.year.year"
                    />
                    <InputError :message="errors.year" />
                </div>

                <div class="submit-buttons">
                    <Button
                        type="submit"
                        :tabindex="2"
                        :disabled="processing"
                        data-test="submit-button"
                    >
                        <Spinner v-if="processing" />
                        Update Year
                    </Button>

                    <Link :href="okrsRoutes.index().url">
                        <Button type="button" variant="outline">
                            Cancel
                        </Button>
                    </Link>

                    <DeleteConfirmationDialog :url="yearsRoutes.destroy(props.year.year).url" title="Delete Year?" description="This year and related content will be deleted permanently!" confirm-text="Delete Year">
                        <template #trigger>
                            <Button type="button" variant="destructive">Delete</Button>
                        </template>
                    </DeleteConfirmationDialog>
                </div>
            </Form>
        </div>

        <!-- Quarters Management -->
        <div class="form">
            <div class="header">
                <h2 class="title">Year Quarters</h2>
                <div class="actions">
                    <Button @click="showBulkForm = !showBulkForm" variant="outline" size="sm">
                        {{ showBulkForm ? 'Cancel Bulk Create' : 'Create All 4 Quarters' }}
                    </Button>
                    <Button @click="showQuarterForm = !showQuarterForm" variant="outline" size="sm">
                        {{ showQuarterForm ? 'Cancel' : 'Add Single Quarter' }}
                    </Button>
                </div>
            </div>

            <!-- Bulk Create Form -->
            <div v-if="showBulkForm" class="bulk-quarters-form">
                <h3 class="text-lg font-medium mb-4">Create All Quarters</h3>
                <div class="space-y-4">
                    <div v-for="(quarter, index) in quartersData" :key="quarter.label" class="border rounded-lg p-4">
                        <h4 class="font-medium mb-3">{{ quarter.label }}</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label :for="`start_${quarter.label}`">Start Date</Label>
                                <Input
                                    :id="`start_${quarter.label}`"
                                    type="date"
                                    v-model="quarter.start_date"
                                />
                            </div>
                            <div>
                                <Label :for="`end_${quarter.label}`">End Date</Label>
                                <Input
                                    :id="`end_${quarter.label}`"
                                    type="date"
                                    v-model="quarter.end_date"
                                />
                            </div>
                        </div>
                    </div>
                    <Button @click="submitBulkQuarters" class="w-full">Create All Quarters</Button>
                </div>
            </div>

            <!-- Individual Quarter Form -->
            <div v-if="showQuarterForm" class="individual-quarter-form">
                <h3 class="text-lg font-medium mb-4">Add Single Quarter</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <Label for="quarter_label">Quarter</Label>
                        <select
                            id="quarter_label"
                            v-model="quarterForm.label"
                            class="w-full px-3 py-2 border rounded-lg"
                        >
                            <option value="">Select Quarter</option>
                            <option value="Q1">Q1 (Jan - Mar)</option>
                            <option value="Q2">Q2 (Apr - Jun)</option>
                            <option value="Q3">Q3 (Jul - Sep)</option>
                            <option value="Q4">Q4 (Oct - Dec)</option>
                        </select>
                    </div>
                    <div>
                        <Label for="start_date">Start Date</Label>
                        <Input id="start_date" type="date" v-model="quarterForm.start_date" />
                    </div>
                    <div>
                        <Label for="end_date">End Date</Label>
                        <Input id="end_date" type="date" v-model="quarterForm.end_date" />
                    </div>
                </div>
                <Button @click="submitQuarter" class="mt-4">Create Quarter</Button>
            </div>

            <!-- Quarters List -->
            <div class="quarters-list mt-6">
                <div v-if="props.year.quarters.length === 0" class="text-center py-8 text-gray-500">
                    No quarters created yet. Use the buttons above to add quarters.
                </div>
                
                <div v-for="quarter in props.year.quarters" :key="quarter.id" class="quarter-item border rounded-lg p-4 mb-3">
                    <div v-if="editingQuarter?.id === quarter.id" class="space-y-4">
                        <h4 class="font-medium">Edit {{ quarter.label }}</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label>Start Date</Label>
                                <Input type="date" v-model="editingQuarter.start_date" />
                            </div>
                            <div>
                                <Label>End Date</Label>
                                <Input type="date" v-model="editingQuarter.end_date" />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Button @click="updateQuarter" size="sm">Save</Button>
                            <Button @click="editingQuarter = null" variant="outline" size="sm">Cancel</Button>
                        </div>
                    </div>
                    
                    <div v-else class="flex justify-between items-center">
                        <div>
                            <h4 class="font-medium">{{ quarter.label }}</h4>
                            <p class="text-sm text-gray-500">{{ getQuarterDates(quarter) }}</p>
                        </div>
                        <div class="flex gap-2">
                            <Button @click="editingQuarter = quarter" variant="outline" size="sm">Edit</Button>
                            <DeleteConfirmationDialog 
                                :url="quartersRoutes.destroy({ year: props.year.year, quarter: quarter.id }).url" 
                                :title="`Delete ${quarter.label}?`" 
                                description="This quarter will be deleted permanently!" 
                                confirm-text="Delete Quarter"
                                @delete="deleteQuarter(quarter)"
                            >
                                <template #trigger>
                                    <Button type="button" variant="destructive" size="sm">Delete</Button>
                                </template>
                            </DeleteConfirmationDialog>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>