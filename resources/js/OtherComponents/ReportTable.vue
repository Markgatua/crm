<script setup>
const data=defineProps({
    chapter:Object,
    desc:String,
})
const firstValues = Object.values(data.chapter)[0]??""
const totalScore = data.chapter.reduce((sum, item) => sum + parseFloat(item.score), 0);

</script>

<template>
    <section class="" style="height: auto; width:100%; ">
        <h3 class="text-center text-red-500 p-4 font-bold">{{firstValues.chapter}} : {{firstValues.chapter_title}}</h3>
        <table class="w-full" style="border: 1px solid green;">
            <thead style="border: 1px solid green;">
            <tr>
                <th style="border: 1px solid green;">Number</th>
                <th style="border: 1px solid green;">Factor</th>
                <th style="border: 1px solid green;">Score(0-5)</th>
                <th style="border: 1px solid green;">Remarks</th>
            </tr>
            </thead>
            <tbody class="p-8">
            <tr style="border: 1px solid green;" v-for="item in data.chapter">
                <td class="w-8 text-center" style="border: 1px solid green;">{{ item.number }}</td>
                <td class="w-1/3" style="border: 1px solid green;">{{ item.item }}</td>
                <td class="w-20 text-center" style="border: 1px solid green;">{{ item.score }}</td>
                <td class="w-auto" style="border: 1px solid green;">{{ item.remarks }}</td>
            </tr>
            <tr style="border: 1px solid green;">
                <td class="w-auto text-red-500 font-bold" style="border: 1px solid green;"></td>
                <td class="w-auto text-red-500 font-bold" style="border: 1px solid green;">Total</td>
                <td class="w-20 text-center" style="border: 1px solid green;">
                  {{totalScore}}/{{data.chapter.length*5}}
                </td>
                <td class="w-auto" style="border: 1px solid green;">
                    {{Math.round((totalScore/((data.chapter.length)*5))*100)}} % </td>

            </tr>
            </tbody>
        </table>
        <h3 class="text-left py-4 text-red-500 font-bold">Recommendations</h3>
        <div class="border border-green-800 mb-8 prose max-w-none" v-html="data.desc">
        </div>
    </section>
</template>
