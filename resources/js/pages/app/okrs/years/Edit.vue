<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Spinner } from '@/components/ui/spinner';
import { Button } from '@/components/ui/button';
import DeleteConfirmationDialog from '@/components/custom/DeleteConfirmation.vue';
import okrsRoutes from '@/routes/okrs';
import yearsRoutes from '@/routes/years';

interface Year {
    id: number;
    year: number;
};

interface Props {
    year: Year;
};

const props = defineProps<Props>();
</script>

<template>
    <Head title="Edit year" />

    <div class="app-container">
        <div class="form">
            <div class="header">
                <Link :href="okrsRoutes.index()">&larr;</Link>
                <h2 class="title">Edit Year</h2>
            </div>

            <Form :action="yearsRoutes.update(year.year).url" method="put" v-slot="{ errors, processing }">
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
    </div>
</template>