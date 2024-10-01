<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, toRefs, toRaw, ref, watchEffect } from 'vue';

const props = defineProps({
    accounts: Object
});

const { accounts } = toRefs(props);

const convertToCSV = (data) => {
    const csvRows = [];

    // Get the keys except 'clientid' and 'clientmaincontactpersons'
    let headers = Object.keys(data[0]).filter(e => e !== 'clientid' && e !== 'clientmaincontactpersons');

    // Add 'contact_persons' field in the header
    headers.push('contact_persons');
    csvRows.push(headers.join(','));

    for (const row of data) {
        const values = headers.map(header => {
            if (header === 'contact_persons') {
                // Parse and extract the fields from the contact_persons
                const contactPersons = JSON.parse(row['clientmaincontactpersons']).map(person => {
                    const { name, email, phone, designation, tag } = person;
                    return `${name ? name : ''},${email ? email : ''},${phone ? phone : ''},${designation ? designation : ''},${tag ? tag : ''}`;
                }).join('|'); // Use a separator like '|' between different persons

                return `"${contactPersons}"`;
            }

            // Escape the other fields as usual
            const escaped = ('' + row[header]).replace(/"/g, '""'); // Escape double quotes
            return `"${escaped}"`;
        });

        csvRows.push(values.join(',')); // Join the values for this row
    }

    return csvRows.join('\n');
}


const exportToExcel = (data) => {
    const csvData = convertToCSV(data);
    const blob = new Blob([csvData], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'xtranet.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function formatClients(clients) {
    if (!clients) {
        return '';
    }
    return clients.replace(/\|\|/g, '<br>');
}


function printDiv() {
    const divToPrint = document.querySelector('.overflow-x-auto').innerHTML;
    const newWindow = window.open('', '', 'height=600,width=800');
    newWindow.document.write('<html><head><title>Xtranet Accounts</title>');
    newWindow.document.write('<style>body { font-family: Arial, sans-serif; margin: 20px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>'); // You can adjust or add styles here
    newWindow.document.write('</head><body >');
    newWindow.document.write(divToPrint);
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    newWindow.focus();
    newWindow.print();
}
</script>

<style scoped>
.truncate {
    white-space: pre-wrap; /* Preserve white spaces and line breaks */
    overflow-wrap: break-word; /* Allow breaking long words */
}
</style>

<template>
    <AppLayout title="Accounts Sheet">
        <PageHeader title="Accounts Sheet" name="Accounts Sheet" />

        <section class="bg-gray-50">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="flex items-center gap-2 w-full md:w-1/2">
                        <!-- Add Print Button here -->
                        <button @click="printDiv" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Print</button>
                        <button @click="exportToExcel([...toRaw(accounts)])" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Excel</button>
                    </div>
                    <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <!-- Add other buttons or actions here if needed -->
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <h2 class="px-3">CONTACTS TABLE</h2>
                    <table class="w-full text-left text-sm text-center text-gray-500 border-separate border-spacing-y-4">
                        <thead class="text-xs text-gray-700 text-center uppercase bg-gray-50">
                            <tr>
                                <th class="px-3 py-3">Client</th>
                                <th class="px-3 py-3">Main Email</th>
                                <th class="px-3 py-3">Main Phone</th>
                                <th class="px-3 py-3">Main Contact Person</th>
                                <th class="px-3 py-3">Account</th>
                                <th class="px-3 py-3">Account Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in accounts" :key="item.id">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ item.clientname }}</td>
                                <td class="px-6 py-4">{{ item.clientemail }}</td>
                                <td class="px-6 py-4">{{ item.clientphone }}</td>
                                <td class="px-6 py-4">
                                    <table class="w-full">
                                        <thead v-if="index == 0">
                                            <tr>
                                                <td class="border text-nowrap p-2 font-bold">Name</td>
                                                <td class="border text-nowrap p-2 font-bold">Email</td>
                                                <td class="border text-nowrap p-2 font-bold">Phone Number</td>
                                                <td class="border text-nowrap p-2 font-bold">Designation</td>
                                                <td class="border text-nowrap p-2 font-bold">Tag</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="it in JSON.parse(item.clientmaincontactpersons)">
                                                <td class="border text-nowrap p-2">{{ it.name }}</td>
                                                <td class="border text-nowrap p-2">{{ it.email }}</td>
                                                <td class="border text-nowrap p-2">{{ it.phone }}</td>
                                                <td class="border text-nowrap p-2">{{ it.designation }}</td>
                                                <td class="border text-nowrap p-2">{{ it.tag }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td class="px-6 py-4">{{ item.account_name }}</td>
                                <td class="px-6 py-4">
                                    <table class="w-full">
                                        <thead v-if="index == 0">
                                            <tr>
                                                <td class="border text-nowrap p-2 font-bold">Name</td>
                                                <td class="border text-nowrap p-2 font-bold">Email</td>
                                                <td class="border text-nowrap p-2 font-bold">Phone Number</td>
                                                <td class="border text-nowrap p-2 font-bold">Designation</td>
                                                <td class="border text-nowrap p-2 font-bold">Tag</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="it in JSON.parse(item.clientmaincontactpersons)">
                                                <td class="border text-nowrap p-2">{{ it.name }}</td>
                                                <td class="border text-nowrap p-2">{{ it.email }}</td>
                                                <td class="border text-nowrap p-2">{{ it.phone }}</td>
                                                <td class="border text-nowrap p-2">{{ it.designation }}</td>
                                                <td class="border text-nowrap p-2">{{ it.tag }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </section>
    </AppLayout>
</template>
