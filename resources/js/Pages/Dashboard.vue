<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import { onMounted, ref, toRefs, computed } from "vue";
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

// Computed helpers for gamification
const funnelStages = computed(() => {
  if (!props.totals) return [];
  const t = props.totals;
  const total = (t.prospects || 0) + (t.scooping || 0) + (t.open || 0) + (t.closed || 0);
  return [
    { label: 'Prospects', count: t.prospects || 0, color: '#6366f1', bg: '#eef2ff', pct: total ? Math.round(((t.prospects||0)/total)*100) : 0 },
    { label: 'Scoping',   count: t.scooping  || 0, color: '#f59e0b', bg: '#fffbeb', pct: total ? Math.round(((t.scooping||0)/total)*100)  : 0 },
    { label: 'Evaluation',count: t.open      || 0, color: '#1A56DB', bg: '#eff6ff', pct: total ? Math.round(((t.open||0)/total)*100)      : 0 },
    { label: 'Closed Won',count: t.closed    || 0, color: '#10b981', bg: '#ecfdf5', pct: total ? Math.round(((t.closed||0)/total)*100)    : 0 },
  ];
});

const medals = ['🥇', '🥈', '🥉'];
const podiumOrder = computed(() => {
  if (!props.topusers || props.topusers.length < 2) return props.topusers || [];
  const sorted = [...props.topusers].slice(0, 3);
  if (sorted.length === 3) return [sorted[1], sorted[0], sorted[2]]; // silver, gold, bronze podium
  return sorted;
});

const podiumHeights = ['h-24', 'h-32', 'h-16'];
const podiumColors  = ['bg-gray-300', 'bg-yellow-400', 'bg-amber-600'];

function initials(user) {
  return ((user.first_name?.[0] ?? '') + (user.last_name?.[0] ?? '')).toUpperCase();
}

function winRate(user) {
  if (!user.accounts_count) return 0;
  return Math.round((user.closed_accounts_count / user.accounts_count) * 100);
}

function avatarColor(idx) {
  const colors = ['bg-indigo-500','bg-yellow-500','bg-amber-700','bg-blue-500','bg-green-500','bg-pink-500'];
  return colors[idx % colors.length];
}
</script>

<template>
  <AppLayout title="Dashboard">
    <!-- ═══════════════════════════════════════════════════════════
         GAMIFIED DASHBOARD
    ═══════════════════════════════════════════════════════════ -->
    <section class="px-4 pb-10 mt-4 sm:px-8 space-y-6">

      <!-- ── TOP BANNER: greeting + quick XP bar ── -->
      <div class="rounded-2xl bg-gradient-to-r from-primary to-indigo-700 text-white px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-lg">
        <div>
          <p class="text-sm font-medium opacity-75">Welcome back,</p>
          <h2 class="text-2xl font-bold">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }} 👋</h2>
          <p class="text-xs mt-1 opacity-70">{{ new Date().toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' }) }}</p>
        </div>
        <div class="flex items-center gap-4">
          <div class="text-right">
            <p class="text-xs opacity-70 mb-1">Team Win Rate</p>
            <p class="text-3xl font-black">
              {{ totals && totals.closed && (totals.closed + (totals.prospects||0) + (totals.scooping||0)) > 0
                  ? Math.round((totals.closed / ((totals.closed||0)+(totals.prospects||0)+(totals.scooping||0)+(totals.open||0))) * 100)
                  : 0 }}%
            </p>
          </div>
          <div class="w-px h-12 bg-white/30"></div>
          <div class="text-right">
            <p class="text-xs opacity-70 mb-1">Pipeline</p>
            <p class="text-3xl font-black">KSH {{ totalrevenueforclosedaccounts ? totalrevenueforclosedaccounts.toLocaleString() : '0' }}</p>
          </div>
        </div>
      </div>

      <!-- ── STAT CARDS ── -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total Accounts -->
        <div class="bg-white rounded-2xl shadow-md p-4 flex flex-col gap-2 hover:shadow-lg transition-shadow">
          <div class="flex items-center justify-between">
            <span class="text-2xl">📁</span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">All</span>
          </div>
          <p class="text-3xl font-black text-gray-800">{{ accounts }}</p>
          <p class="text-xs text-gray-500 font-medium">Total Accounts</p>
          <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 rounded-full bg-blue-500" style="width:100%"></div></div>
        </div>

        <!-- Prospects -->
        <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4"
             class="bg-white rounded-2xl shadow-md p-4 flex flex-col gap-2 hover:shadow-lg transition-shadow">
          <div class="flex items-center justify-between">
            <span class="text-2xl">🔭</span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600">Stage 1</span>
          </div>
          <p class="text-3xl font-black text-gray-800">{{ totals?.prospects ?? 0 }}</p>
          <p class="text-xs text-gray-500 font-medium">Prospects</p>
          <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 rounded-full bg-indigo-500" :style="`width:${accounts ? Math.min(100, Math.round(((totals?.prospects||0)/accounts)*100)) : 0}%`"></div></div>
        </div>

        <!-- Scoping -->
        <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4"
             class="bg-white rounded-2xl shadow-md p-4 flex flex-col gap-2 hover:shadow-lg transition-shadow">
          <div class="flex items-center justify-between">
            <span class="text-2xl">🔍</span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">Stage 2</span>
          </div>
          <p class="text-3xl font-black text-gray-800">{{ totals?.scooping ?? 0 }}</p>
          <p class="text-xs text-gray-500 font-medium">Scoping</p>
          <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 rounded-full bg-amber-500" :style="`width:${accounts ? Math.min(100, Math.round(((totals?.scooping||0)/accounts)*100)) : 0}%`"></div></div>
        </div>

        <!-- Closed Won -->
        <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4"
             class="bg-white rounded-2xl shadow-md p-4 flex flex-col gap-2 hover:shadow-lg transition-shadow">
          <div class="flex items-center justify-between">
            <span class="text-2xl">🏆</span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-50 text-green-600">Won</span>
          </div>
          <p class="text-3xl font-black text-gray-800">{{ totals?.closed ?? 0 }}</p>
          <p class="text-xs text-gray-500 font-medium">Closed Won</p>
          <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 rounded-full bg-green-500" :style="`width:${accounts ? Math.min(100, Math.round(((totals?.closed||0)/accounts)*100)) : 0}%`"></div></div>
        </div>

        <!-- Lost -->
        <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4"
             class="bg-white rounded-2xl shadow-md p-4 flex flex-col gap-2 hover:shadow-lg transition-shadow">
          <div class="flex items-center justify-between">
            <span class="text-2xl">💨</span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-50 text-red-500">Lost</span>
          </div>
          <p class="text-3xl font-black text-gray-800">{{ totals?.lost ?? 0 }}</p>
          <p class="text-xs text-gray-500 font-medium">Lost Accounts</p>
          <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 rounded-full bg-red-400" :style="`width:${accounts ? Math.min(100, Math.round(((totals?.lost||0)/accounts)*100)) : 0}%`"></div></div>
        </div>

        <!-- Sales People -->
        <div class="bg-white rounded-2xl shadow-md p-4 flex flex-col gap-2 hover:shadow-lg transition-shadow">
          <div class="flex items-center justify-between">
            <span class="text-2xl">👥</span>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-purple-50 text-purple-600">Team</span>
          </div>
          <p class="text-3xl font-black text-gray-800">{{ personnels }}</p>
          <p class="text-xs text-gray-500 font-medium">Sales People</p>
          <div class="h-1.5 rounded-full bg-gray-100"><div class="h-1.5 rounded-full bg-purple-500" style="width:100%"></div></div>
        </div>
      </div>

      <!-- ── ROW 2: Funnel + Leaderboard ── -->
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-6" v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4">

        <!-- SALES FUNNEL -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md p-6">
          <div class="flex items-center gap-2 mb-6">
            <span class="text-xl">🎯</span>
            <h3 class="font-bold text-gray-800 text-lg">Sales Pipeline Funnel</h3>
          </div>
          <div class="space-y-3">
            <div v-for="(stage, i) in funnelStages" :key="stage.label"
                 class="relative">
              <!-- funnel bar - narrowing with each level -->
              <div class="flex items-center gap-3">
                <div class="flex-1" :style="`padding: 0 ${i * 7}%`">
                  <div class="rounded-xl px-4 py-3 flex items-center justify-between transition-all"
                       :style="`background:${stage.bg}; border-left: 4px solid ${stage.color}`">
                    <div class="flex items-center gap-2">
                      <span class="text-sm font-bold" :style="`color:${stage.color}`">
                        {{ ['🔭','🔍','📊','🏆'][i] }} {{ stage.label }}
                      </span>
                    </div>
                    <div class="text-right">
                      <span class="text-xl font-black" :style="`color:${stage.color}`">{{ stage.count }}</span>
                      <span class="text-xs ml-1 opacity-60" :style="`color:${stage.color}`">{{ stage.pct }}%</span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- arrow connector -->
              <div v-if="i < funnelStages.length - 1" class="flex justify-center py-1">
                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </div>
            </div>
          </div>
          <!-- conversion rate badge -->
          <div class="mt-6 rounded-xl bg-green-50 border border-green-100 p-3 flex items-center justify-between">
            <span class="text-sm font-semibold text-green-700">🎉 Conversion Rate</span>
            <span class="text-lg font-black text-green-700">
              {{ funnelStages[0]?.count ? Math.round(((funnelStages[3]?.count||0)/funnelStages[0].count)*100) : 0 }}%
            </span>
          </div>
        </div>

        <!-- LEADERBOARD PODIUM -->
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-md p-6">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
              <span class="text-xl">🏅</span>
              <h3 class="font-bold text-gray-800 text-lg">Sales Leaderboard</h3>
            </div>
            <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full font-medium">This Period</span>
          </div>

          <!-- Podium (top 3) -->
          <div v-if="topusers && topusers.length >= 2" class="flex items-end justify-center gap-3 mb-8">
            <!-- 2nd place (left) -->
            <div v-if="topusers[1]" class="flex flex-col items-center gap-2 flex-1">
              <span class="text-2xl">🥈</span>
              <div class="w-12 h-12 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-gray-200">
                {{ initials(topusers[1]) }}
              </div>
              <p class="text-xs font-semibold text-center text-gray-700 leading-tight">{{ topusers[1].first_name }}<br>{{ topusers[1].last_name }}</p>
              <p class="text-xs text-gray-500 font-medium">{{ topusers[1].accounts_count }} accts</p>
              <div class="w-full bg-gray-200 rounded-t-lg h-20 flex items-end justify-center pb-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-gray-300 to-gray-400"></div>
                <span class="relative text-white font-black text-xl">2</span>
              </div>
            </div>

            <!-- 1st place (center, tallest) -->
            <div v-if="topusers[0]" class="flex flex-col items-center gap-2 flex-1">
              <div class="relative">
                <span class="text-3xl">🥇</span>
                <span class="absolute -top-1 -right-1 text-xs animate-bounce">✨</span>
              </div>
              <div class="w-14 h-14 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold text-base shadow-lg ring-3 ring-yellow-300 ring-offset-2">
                {{ initials(topusers[0]) }}
              </div>
              <p class="text-xs font-bold text-center text-gray-800 leading-tight">{{ topusers[0].first_name }}<br>{{ topusers[0].last_name }}</p>
              <p class="text-xs text-yellow-600 font-bold">{{ topusers[0].accounts_count }} accts</p>
              <div class="w-full bg-yellow-400 rounded-t-lg h-32 flex items-end justify-center pb-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-yellow-300 to-yellow-500"></div>
                <span class="relative text-white font-black text-2xl">1</span>
              </div>
            </div>

            <!-- 3rd place (right) -->
            <div v-if="topusers[2]" class="flex flex-col items-center gap-2 flex-1">
              <span class="text-2xl">🥉</span>
              <div class="w-12 h-12 rounded-full bg-amber-600 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-amber-200">
                {{ initials(topusers[2]) }}
              </div>
              <p class="text-xs font-semibold text-center text-gray-700 leading-tight">{{ topusers[2].first_name }}<br>{{ topusers[2].last_name }}</p>
              <p class="text-xs text-gray-500 font-medium">{{ topusers[2].accounts_count }} accts</p>
              <div class="w-full bg-amber-600 rounded-t-lg h-14 flex items-end justify-center pb-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-amber-500 to-amber-700"></div>
                <span class="relative text-white font-black text-xl">3</span>
              </div>
            </div>
          </div>

          <!-- Full ranked list -->
          <div class="space-y-2">
            <div v-for="(user, idx) in topusers" :key="user.id"
                 class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors"
                 :class="idx === 0 ? 'bg-yellow-50 border border-yellow-100' : 'hover:bg-gray-50'">
              <span class="text-base w-6 text-center">{{ medals[idx] || `#${idx+1}` }}</span>
              <div :class="`w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold ${avatarColor(idx)}`">
                {{ initials(user) }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ user.first_name }} {{ user.last_name }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                  <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-primary to-indigo-500 transition-all"
                         :style="`width:${topusers[0]?.accounts_count ? Math.round((user.accounts_count/topusers[0].accounts_count)*100) : 0}%`"></div>
                  </div>
                  <span class="text-xs text-gray-400">{{ winRate(user) }}% win</span>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-black text-gray-800">{{ user.accounts_count }}</p>
                <p class="text-xs text-gray-400">accounts</p>
              </div>
              <div class="flex gap-1">
                <span v-if="user.closed_accounts_count > 0" title="Closed deals" class="text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded-md font-semibold">✓{{ user.closed_accounts_count }}</span>
                <span v-if="user.overdue_accounts_count > 0" title="Overdue" class="text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded-md font-semibold">⚠{{ user.overdue_accounts_count }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── ROW 3: Charts ── -->
      <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center gap-2 mb-4">
          <span class="text-xl">📈</span>
          <h3 class="font-bold text-gray-800">Account Growth</h3>
        </div>
        <div ref="lineChart" style="width:100%;height:280px"></div>
      </div>
      <!-- Revenue Breakdown — full width -->
      <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center gap-2 mb-4">
          <span class="text-xl">💰</span>
          <h3 class="font-bold text-gray-800">Revenue Breakdown</h3>
        </div>
        <div ref="pieChart" style="width:100%;height:360px"></div>
      </div>

      <!-- ── ROW 4: Top Revenue Earners (gamified) + Recent Sales ── -->
      <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4"
           class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        <!-- Top Revenue Cards -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md p-6">
          <div class="flex items-center gap-2 mb-5">
            <span class="text-xl">💎</span>
            <h3 class="font-bold text-gray-800 text-lg">Top Revenue</h3>
          </div>
          <div class="space-y-3">
            <div v-for="(item, idx) in topsales" :key="item.id"
                 class="flex items-center gap-3 p-3 rounded-xl border"
                 :class="idx===0 ? 'border-yellow-200 bg-yellow-50' : idx===1 ? 'border-gray-200 bg-gray-50' : 'border-amber-100 bg-amber-50'">
              <div class="text-2xl">{{ ['🥇','🥈','🥉'][idx] }}</div>
              <div :class="`w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm ${avatarColor(idx)}`">
                {{ ((item.first_name?.[0]??'')+(item.last_name?.[0]??'')).toUpperCase() }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm truncate">{{ item.first_name }} {{ item.last_name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ item.clientname || item.email }}</p>
              </div>
              <div class="text-right">
                <p class="font-black text-gray-800 text-sm">KSH {{ Number(item.amount).toLocaleString() }}</p>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                      :class="idx===0 ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-200 text-gray-700'">
                  {{ idx===0 ? '🔥 Top' : `#${idx+1}` }}
                </span>
              </div>
            </div>
            <div v-if="!topsales || topsales.length === 0" class="text-center py-6 text-gray-400">
              <p class="text-4xl mb-2">🎯</p>
              <p class="text-sm">No sales recorded yet.<br>Be the first to close a deal!</p>
            </div>
          </div>
        </div>

        <!-- Recent Sales Feed -->
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-md p-6">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
              <span class="text-xl">⚡</span>
              <h3 class="font-bold text-gray-800 text-lg">Recent Wins</h3>
            </div>
            <span class="flex items-center gap-1 text-xs text-green-600 bg-green-50 px-3 py-1 rounded-full font-semibold">
              <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
              Live
            </span>
          </div>
          <div class="space-y-3">
            <div v-for="(item, idx) in recentsales" :key="item.id"
                 class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-blue-50 transition-colors group">
              <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ idx+1 }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm truncate">{{ item.business_name || item.name }}</p>
                <p class="text-xs text-gray-500">{{ item.first_name }} {{ item.last_name }} · {{ new Date(item.date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) }}</p>
              </div>
              <div class="text-right flex-shrink-0">
                <p class="font-black text-green-600 text-sm">KSH {{ Number(item.amount).toLocaleString() }}</p>
              </div>
              <Link :href="`/accounts/account/${item.account_id}`"
                    class="opacity-0 group-hover:opacity-100 transition-opacity text-xs text-primary font-semibold">
                View →
              </Link>
            </div>
            <div v-if="!recentsales || recentsales.length === 0" class="text-center py-6 text-gray-400">
              <p class="text-4xl mb-2">💤</p>
              <p class="text-sm">No recent sales. Time to hustle!</p>
            </div>
          </div>
        </div>
      </div>

      <!-- ── ROW 5: Full Team Breakdown Table (admin only) ── -->
      <div v-if="$page.props.auth.user.role_id === 1"
           class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center gap-2 mb-5">
          <span class="text-xl">📊</span>
          <h3 class="font-bold text-gray-800 text-lg">Full Team Scorecard</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="border-b border-gray-100">
                <th class="py-3 text-left text-xs font-semibold text-gray-500 uppercase pr-4">Rank</th>
                <th class="py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rep</th>
                <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase">New</th>
                <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase">Won 🏆</th>
                <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase">Lost 💨</th>
                <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase">Overdue ⚠</th>
                <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase">Open</th>
                <th class="py-3 text-right text-xs font-semibold text-gray-500 uppercase">Win %</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-for="(user, idx) in topusers" :key="user.id"
                  class="hover:bg-gray-50 transition-colors"
                  :class="idx===0 ? 'bg-yellow-50/50' : ''">
                <td class="py-3 pr-4">
                  <span class="text-base">{{ medals[idx] || `#${idx+1}` }}</span>
                </td>
                <td class="py-3">
                  <div class="flex items-center gap-2">
                    <div :class="`w-7 h-7 rounded-full ${avatarColor(idx)} flex items-center justify-center text-white text-xs font-bold`">
                      {{ initials(user) }}
                    </div>
                    <span class="font-semibold text-gray-800">{{ user.first_name }} {{ user.last_name }}</span>
                    <span v-if="idx===0" class="text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded font-bold">MVP</span>
                  </div>
                </td>
                <td class="py-3 text-right font-bold text-gray-800">{{ user.accounts_count }}</td>
                <td class="py-3 text-right text-blue-600">{{ user.new_accounts_count }}</td>
                <td class="py-3 text-right text-green-600 font-semibold">{{ user.closed_accounts_count }}</td>
                <td class="py-3 text-right text-red-500">{{ user.lost_accounts_count }}</td>
                <td class="py-3 text-right text-orange-500">{{ user.overdue_accounts_count }}</td>
                <td class="py-3 text-right text-gray-500">{{ user.open_accounts_count }}</td>
                <td class="py-3 text-right">
                  <span class="font-bold px-2 py-0.5 rounded-full text-xs"
                        :class="winRate(user)>=50 ? 'bg-green-100 text-green-700' : winRate(user)>=25 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600'">
                    {{ winRate(user) }}%
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </section>
  </AppLayout>
</template>
