<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import { onMounted, ref, toRefs } from "vue";
import * as echarts from "echarts";
import { Link } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";

const props = defineProps({
  accounts: Object,
  personnels: Object,
  newaccounts: Object,
  clients: Object,
  topsales: Object,
  topusers: Object,
  recentsales: Object,
  popularprojects: Object,
  accountsgraph: Object,
  revenuesgraph: Object,
  scopingaccounts: Object,
  closedaccounts: Object,
  lostaccounts: Object,
  totals: Object,
  totalrevenueforclosedaccounts: Object,
  totalrevenueforevaluationaccounts: Object,
});

const { accountsgraph, revenuesgraph } = toRefs(props);

const accountsMonth = accountsgraph.value.map((item) => item.month);
const accountsTotal = accountsgraph.value.map((item) => item.accounts_count);
const accountsOpen = accountsgraph.value.map(
  (item) => item.open_accounts_count
);
const accountsClosed = accountsgraph.value.map(
  (item) => item.closed_accounts_count
);
const accountsNew = accountsgraph.value.map((item) => item.new_accounts_count);
const accountsLost = accountsgraph.value.map(
  (item) => item.lost_accounts_count
);
const accountsOverdue = accountsgraph.value.map(
  (item) => item.overdue_accounts_count
);
const totalRevenueMonth = revenuesgraph.value.map((item) => item.month);
const totalRevenue = revenuesgraph.value.map((item) =>
  Number.parseFloat(item.total_revenue)
);

const lineChart = ref(null);
const pieChart = ref(null);

onMounted(() => {
  let app = {};

  const myChart1 = echarts.init(lineChart.value);
  const myChart2 = echarts.init(pieChart.value);

  let option;

  myChart1.setOption({
    tooltip: {
      trigger: "axis",
    },
    legend: {
      data: [
        "All Accounts",
        "Open Accounts",
        "Closed Accounts",
        "Lost Accounts",
        "Overdue Accounts",
      ],
    },
    grid: {
      left: "3%",
      right: "4%",
      bottom: "3%",
      containLabel: true,
    },
    toolbox: {
      feature: {
        saveAsImage: {},
      },
    },
    xAxis: {
      type: "category",
      boundaryGap: false,
      data: [...accountsMonth],
    },
    yAxis: {
      type: "value",
    },
    series: [
      {
        name: "All Accounts",
        type: "line",
        data: [...accountsTotal],
      },
      {
        name: "Open Accounts",
        type: "line",
        data: [...accountsOpen],
      },
      {
        name: "Closed Accounts",
        type: "line",
        data: [...accountsClosed],
      },
      {
        name: "Lost Accounts",
        type: "line",
        data: [...accountsLost],
      },
      {
        name: "Overdue Accounts",
        type: "line",
        data: [...accountsOverdue],
      },
    ],
  });

  const posList = [
    "left",
    "right",
    "top",
    "bottom",
    "inside",
    "insideTop",
    "insideLeft",
    "insideRight",
    "insideBottom",
    "insideTopLeft",
    "insideTopRight",
    "insideBottomLeft",
    "insideBottomRight",
  ];
  app.configParameters = {
    rotate: {
      min: -90,
      max: 90,
    },
    align: {
      options: {
        left: "left",
        center: "center",
        right: "right",
      },
    },
    verticalAlign: {
      options: {
        top: "top",
        middle: "middle",
        bottom: "bottom",
      },
    },
    position: {
      options: posList.reduce((map, pos) => {
        map[pos] = pos;
        return map;
      }, {}),
    },
    distance: {
      min: 0,
      max: 100,
    },
  };
  app.config = {
    rotate: 90,
    align: "left",
    verticalAlign: "middle",
    position: "insideBottom",
    distance: 15,
    onChange: () => {
      const labelOption = {
        rotate: app.config.rotate,
        align: app.config.align,
        verticalAlign: app.config.verticalAlign,
        position: app.config.position,
        distance: app.config.distance,
      };
      myChart2.setOption({
        series: [
          {
            label: labelOption,
          },
          {
            label: labelOption,
          },
          {
            label: labelOption,
          },
          {
            label: labelOption,
          },
        ],
      });
    },
  };
  const labelOption = {
    show: true,
    position: app.config.position,
    distance: app.config.distance,
    align: app.config.align,
    verticalAlign: app.config.verticalAlign,
    rotate: app.config.rotate,
    formatter: "{c}  {name|{a}}",
    fontSize: 16,
    rich: {
      name: {},
    },
  };
  option = {
    tooltip: {
      trigger: "axis",
      axisPointer: {
        type: "shadow",
      },
    },
    legend: {
      data: [
        "All Accounts",
        "New Accounts",
        "Closed Accounts",
        "Lost Accounts",
        "Overdue Accounts",
      ],
    },
    toolbox: {
      show: true,
      orient: "vertical",
      left: "right",
      top: "center",
      feature: {
        mark: { show: true },
        dataView: { show: true, readOnly: false },
        magicType: { show: true, type: ["line", "bar", "stack"] },
        restore: { show: true },
        saveAsImage: { show: true },
      },
    },
    xAxis: [
      {
        type: "category",
        axisTick: { show: false },
        data: [...accountsMonth],
      },
    ],
    yAxis: [
      {
        type: "value",
      },
    ],
    series: [
      {
        name: "All Accounts",
        type: "bar",
        barGap: 0,
        label: labelOption,
        emphasis: {
          focus: "series",
        },
        data: [...accountsTotal],
      },
      {
        name: "New Accounts",
        type: "bar",
        label: labelOption,
        emphasis: {
          focus: "series",
        },
        data: [...accountsNew],
      },
      {
        name: "Closed Accounts",
        type: "bar",
        label: labelOption,
        emphasis: {
          focus: "series",
        },
        data: [...accountsClosed],
      },
      {
        name: "Lost Accounts",
        type: "bar",
        label: labelOption,
        emphasis: {
          focus: "series",
        },
        data: [...accountsLost],
      },
      {
        name: "Overdue Accounts",
        type: "bar",
        label: labelOption,
        emphasis: {
          focus: "series",
        },
        data: [...accountsLost],
      },
    ],
  };

  option && myChart2.setOption(option);
});
</script>

<template>
  <AppLayout title="Dashboard">
    <PageHeader title="Dashboard" name="dashboard" />
    <section class="px-4 mt-8 sm:px-8">
      <div class="grid grid-cols-1 gap-4 mt-8 sm:grid-cols-4">
        <div
          class="flex items-center bg-white rounded-sm overflow-hidden shadow"
        >
          <div class="p-4 bg-green-400">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
              />
            </svg>
          </div>
          <div class="px-4 text-gray-700">
            <h3 class="text-sm tracking-wider">Total Accounts</h3>
            <p class="text-3xl">{{ accounts }}</p>
          </div>
        </div>

        <div
          v-if="
            $page.props.auth.user.role_id === 1 ||
            $page.props.auth.user.role_id === 4
          "
          class="flex items-center bg-white rounded-sm overflow-hidden shadow"
        >
          <div class="p-4 bg-red">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
              />
            </svg>
          </div>
          <div class="px-4 text-gray-700">
            <h3 class="text-sm tracking-wider">Closed Accounts</h3>
            <p class="text-3xl">{{ totals.closed }}</p>
          </div>
        </div>

        <div
          v-if="
            $page.props.auth.user.role_id === 1 ||
            $page.props.auth.user.role_id === 4
          "
          class="flex items-center bg-white rounded-sm overflow-hidden shadow"
        >
          <div class="p-4 bg-red">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
              />
            </svg>
          </div>
          <div class="px-4 text-gray-700">
            <h3 class="text-sm tracking-wider">Scoping Accounts</h3>
            <p class="text-3xl">{{ totals.scooping }}</p>
          </div>
        </div>

        <div
          v-if="
            $page.props.auth.user.role_id === 1 ||
            $page.props.auth.user.role_id === 4
          "
          class="flex items-center bg-white rounded-sm overflow-hidden shadow"
        >
          <div class="p-4 bg-red">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
              />
            </svg>
          </div>
          <div class="px-4 text-gray-700">
            <h3 class="text-sm tracking-wider">Lost Accounts</h3>
            <p class="text-3xl">{{ totals.lost }}</p>
          </div>
        </div>

        <div
          v-if="
            $page.props.auth.user.role_id === 1 ||
            $page.props.auth.user.role_id === 4
          "
          class="flex items-center bg-white rounded-sm overflow-hidden shadow"
        >
          <div class="p-4 bg-red">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
              />
            </svg>
          </div>
          <div class="px-4 text-gray-700">
            <h3 class="text-sm tracking-wider">Prospects Accounts</h3>
            <p class="text-3xl">{{ totals.prospects }}</p>
          </div>
        </div>

        <div
          class="flex items-center bg-white rounded-sm overflow-hidden shadow"
        >
          <div class="p-4 bg-red">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
              />
            </svg>
          </div>
          <div
            class="px-4 text-gray-700"
            v-if="
              $page.props.auth.user.role_id === 1 ||
              $page.props.auth.user.role_id === 5
            "
          >
            <h3 class="text-sm tracking-wider">Sales Personnels</h3>
            <p class="text-3xl">{{ personnels }}</p>
          </div>
          <div
            class="px-4 text-gray-700"
            v-if="$page.props.auth.user.role_id === 4"
          >
            <h3 class="text-sm tracking-wider">My Colleagues</h3>
            <p class="text-3xl">{{ personnels - 1 }}</p>
          </div>
        </div>

        <div
          v-if="
            $page.props.auth.user.role_id === 1 ||
            $page.props.auth.user.role_id === 4
          "
          class="flex items-center bg-white rounded-sm overflow-hidden shadow"
        >
          <div class="p-2 bg-red">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <text
                x="4"
                y="20"
                font-family="Arial, sans-serif"
                font-size="9"
                fill="currentColor"
              >
                KSH
              </text>
            </svg>
          </div>
          <div class="px-4 text-gray-700">
            <h3 class="text-sm tracking-wider">Revenue From Closed Accounts</h3>
            <p class="text-xl">
              {{ totalrevenueforclosedaccounts.toLocaleString() }}
            </p>
          </div>
        </div>

        <div
          v-if="
            $page.props.auth.user.role_id === 1 ||
            $page.props.auth.user.role_id === 4
          "
          class="flex items-center bg-white rounded-sm overflow-hidden shadow"
        >
          <div class="p-4 bg-red">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12 text-white"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <text
                x="4"
                y="20"
                font-family="Arial, sans-serif"
                font-size="9"
                fill="currentColor"
              >
                KSH
              </text>
            </svg>
          </div>
          <div class="px-4 text-gray-700">
            <h3 class="text-sm tracking-wider">
              Evaluation Stage Expected Sales
            </h3>
            <p class="text-xl">
              {{ totalrevenueforevaluationaccounts.toLocaleString() }}
            </p>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4 mt-8 sm:grid-cols-2">
        <div class="px-4 py-2 bg-white rounded-md overflow-hidden shadow">
          <h3 class="text-xl text-gray-600 mb-4">Accounts</h3>
          <div ref="lineChart" style="width: 100%; height: 300px"></div>
        </div>

        <div class="px-4 py-2 bg-white rounded-md overflow-hidden shadow">
                    <h3 class="text-xl text-gray-600 mb-4">Revenue</h3>
                    <div ref="pieChart" style="width: 100%; height: 300px;"></div>
                </div>

        <div class="grid grid-rows-1 gap-4">
          <!-- <div class="px-4 py-2 bg-white rounded-md shadow">
                        <h3 class="text-xl text-gray-600 mb-2">Popular Accounts</h3>

                        <div class="flex flex-col">
                            <div class="align-middle inline-block min-w-full">
                                <div class="overflow-hidden">
                                    <table class="table-auto min-w-full divide-y divide-gray-200">
                                        <thead>
                                        <tr>
                                            <th scope="col"
                                                class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Account
                                            </th>
                                            <th scope="col"
                                                class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Business
                                            </th>

                                            <th scope="col"
                                                class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Stage
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 text-sm text-gray-500">
                                        <tr v-for="item in popularprojects" :key="item.id">
                                            <td class="py-1">{{ item.clientname }}</td>
                                            <td class="py-1">{{ item.business_name }}</td>
                                            <td class="py-1 text-sm text-gray-500">{{ item.stage }}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> -->
          <div
            v-if="$page.props.auth.user.role_id === 1"
            class="px-4 py-2 bg-white rounded-md shadow"
          >
            <h3 class="text-xl text-gray-600 mb-2">Sales</h3>

            <div class="flex flex-col">
              <div class="align-middle inline-block min-w-full">
                <div class="overflow-hidden">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                      <tr>
                        <th
                          scope="col"
                          class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Name
                        </th>

                        <th
                          scope="col"
                          class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Total Accounts
                        </th>
                        <th
                          scope="col"
                          class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          New Accounts
                        </th>

                        <th
                          scope="col"
                          class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Closed Accounts
                        </th>
                        <th
                          scope="col"
                          class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Lost Accounts
                        </th>
                        <th
                          scope="col"
                          class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Overdue Accounts
                        </th>

                        <th
                          scope="col"
                          class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Open Accounts
                        </th>
                      </tr>
                    </thead>
                    <tbody
                      class="divide-y divide-gray-200 text-sm text-gray-500"
                    >
                      <tr v-for="item in topusers" :key="item.id">
                        <td class="py-1 whitespace-nowrap">
                          {{ item.first_name }} {{ item.last_name }}
                        </td>
                        <td
                          class="py-1 text-right whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ item.accounts_count }}
                        </td>
                        <td
                          class="py-1 text-right whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ item.new_accounts_count }}
                        </td>
                        <td
                          class="py-1 text-right whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ item.closed_accounts_count }}
                        </td>
                        <td
                          class="py-1 text-right whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ item.lost_accounts_count }}
                        </td>
                        <td
                          class="py-1 text-right whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ item.overdue_accounts_count }}
                        </td>
                        <td
                          class="py-1 text-right whitespace-nowrap text-sm text-gray-500"
                        >
                          {{ item.open_accounts_count }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="
          $page.props.auth.user.role_id === 1 ||
          $page.props.auth.user.role_id === 4
        "
        class="px-4 py-2 bg-white rounded-md overflow-hidden shadow mt-10"
      >
        <h3 class="text-xl text-gray-600 mb-4">Accounts</h3>
        <div ref="pieChart" style="width: 100%; height: 500px"></div>
      </div>

      <div
        v-if="
          $page.props.auth.user.role_id === 1 ||
          $page.props.auth.user.role_id === 4
        "
        class="grid grid-cols-1 gap-4 mt-8 sm:grid-cols-5"
      >
        <TabGroup>
          <div class="bg-white rounded-lg shadow sm:col-span-2">
            <div
              class="flex flex-col space-y-2 items-center px-4 mb-2 py-2 text-gray-600 sm:flex-row sm:justify-between"
            >
              <h3 class="tracking-wider">Top Sales</h3>
              <TabList class="flex space-x-1"> </TabList>
            </div>

            <TabPanels class="px-4 py-2">
              <TabPanel v-for="topAuthor in topAuthors" :key="topAuthor">
                <table class="min-w-full text-gray-500">
                  <tbody>
                    <tr v-for="item in topsales" :key="item.id">
                      <td class="flex items-center py-2">
                        <img
                          class="inline-block h-12 w-12 rounded-full ring-2 ring-white"
                          src="/usericon.png"
                          alt=""
                        />
                        <div class="px-4">
                          <div>{{ item.first_name }} {{ item.last_name }}</div>
                          <div class="font-bold text-sm">
                            <a href="#">{{ item.email }}</a>
                          </div>
                        </div>
                      </td>
                      <td class="py-2 text-right">
                        <div class="text-2xl"></div>
                        <div class="text-sm">{{ item.amount }}</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </TabPanel>
            </TabPanels>
          </div>
        </TabGroup>

        <div
          v-if="!$page.props.auth.user.role_id === 6"
          class="bg-white rounded-lg shadow sm:col-span-3"
        >
          <div
            class="flex justify-between items-center px-4 py-2 mb-2 text-gray-600"
          >
            <h3 class="tracking-wider">Recent Sales</h3>
          </div>
          <div class="px-4">
            <table class="table-fixed min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th
                    scope="col"
                    class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Account
                  </th>

                  <th
                    scope="col"
                    class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Date
                  </th>

                  <th
                    scope="col"
                    class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Amount
                  </th>

                  <th
                    scope="col"
                    class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Sales Person
                  </th>

                  <th
                    scope="col"
                    class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Action
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 text-sm text-gray-500">
                <tr v-for="item in recentsales" :key="item.id">
                  <td class="py-4 whitespace-nowrap">{{ item.name }}</td>
                  <td class="py-4 whitespace-nowrap text-sm text-gray-500">
                    <span></span>{{ new Date(item.date).toDateString() }}
                  </td>
                  <td class="py-4 whitespace-nowrap text-sm text-gray-500">
                    KSH {{ item.amount }}
                  </td>
                  <td class="py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ item.first_name }} {{ item.last_name }}
                  </td>
                  <td class="py-4 whitespace-nowrap text-sm text-red-600">
                    <Link :href="`/accounts/account/${item.account_id}`"
                      >View Detail</Link
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
