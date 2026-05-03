<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Spinner } from '@/components/ui/spinner';
import { Button } from '@/components/ui/button';
import okrsRoutes from '@/routes/okrs';
import yearsRoutes from '@/routes/years';
</script>

<template>
    <Head title="Create year" />

    <div class="app-container">
        <div class="form">
            <div class="header">
                <Link :href="okrsRoutes.index()">&larr;</Link>
                <h2 class="title">Create Year</h2>
            </div>

            <Form :action="yearsRoutes.store().url" method="post" v-slot="{ errors, processing }">
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
                        Create Year
                    </Button>

                    <Link :href="okrsRoutes.index().url">
                        <Button type="button" variant="outline">
                            Cancel
                        </Button>
                    </Link>
                </div>
            </Form>
        </div>
    </div>
</template>