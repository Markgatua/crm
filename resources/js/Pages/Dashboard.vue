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
  const myChart1 = echarts.init(lineChart.value);
  const myChart2 = echarts.init(pieChart.value);

  // Elegant line chart options mapping to our brand palette
  myChart1.setOption({
    color: ['#1A56DB', '#10B981', '#D97706', '#EF4444', '#6B7280'],
    tooltip: {
      trigger: "axis",
      backgroundColor: 'rgba(255, 255, 255, 0.95)',
      borderColor: '#E5E7EB',
      borderWidth: 1,
      textStyle: { color: '#1F2937', fontSize: 12 },
      shadowColor: 'rgba(0, 0, 0, 0.05)',
      shadowBlur: 10,
    },
    legend: {
      data: ["All Accounts", "Open Accounts", "Closed Accounts", "Lost Accounts", "Overdue Accounts"],
      textStyle: { color: '#4B5563', fontSize: 11, fontFamily: 'Inter' },
      itemGap: 16,
      icon: 'rect',
      itemWidth: 10,
      itemHeight: 10,
      borderRadius: 2
    },
    grid: {
      left: "2%",
      right: "2%",
      bottom: "2%",
      top: "15%",
      containLabel: true,
    },
    xAxis: {
      type: "category",
      boundaryGap: false,
      data: [...accountsMonth],
      axisLine: { lineStyle: { color: '#E5E7EB' } },
      axisLabel: { color: '#6B7280', fontSize: 11, fontFamily: 'Inter' }
    },
    yAxis: {
      type: "value",
      splitLine: { lineStyle: { color: '#F3F4F6' } },
      axisLabel: { color: '#6B7280', fontSize: 11, fontFamily: 'Inter' }
    },
    series: [
      { name: "All Accounts", type: "line", smooth: true, showSymbol: false, lineStyle: { width: 3 }, data: [...accountsTotal] },
      { name: "Open Accounts", type: "line", smooth: true, showSymbol: false, lineStyle: { width: 2 }, data: [...accountsOpen] },
      { name: "Closed Accounts", type: "line", smooth: true, showSymbol: false, lineStyle: { width: 2 }, data: [...accountsClosed] },
      { name: "Lost Accounts", type: "line", smooth: true, showSymbol: false, lineStyle: { width: 2 }, data: [...accountsLost] },
      { name: "Overdue Accounts", type: "line", smooth: true, showSymbol: false, lineStyle: { width: 2 }, data: [...accountsOverdue] },
    ],
  });

  const labelOption = {
    show: false
  };

  const option = {
    color: ['#1A56DB', '#60A5FA', '#10B981', '#EF4444', '#F59E0B'],
    tooltip: {
      trigger: "axis",
      axisPointer: { type: "shadow" },
      backgroundColor: 'rgba(255, 255, 255, 0.95)',
      borderColor: '#E5E7EB',
      borderWidth: 1,
    },
    legend: {
      data: ["All Accounts", "New Accounts", "Closed Accounts", "Lost Accounts", "Overdue Accounts"],
      textStyle: { color: '#4B5563', fontSize: 11, fontFamily: 'Inter' },
      itemGap: 16
    },
    grid: {
      left: "2%",
      right: "2%",
      bottom: "2%",
      top: "15%",
      containLabel: true,
    },
    xAxis: [
      {
        type: "category",
        axisTick: { show: false },
        data: [...accountsMonth],
        axisLine: { lineStyle: { color: '#E5E7EB' } },
        axisLabel: { color: '#6B7280', fontSize: 11, fontFamily: 'Inter' }
      },
    ],
    yAxis: [
      {
        type: "value",
        splitLine: { lineStyle: { color: '#F3F4F6' } },
        axisLabel: { color: '#6B7280', fontSize: 11, fontFamily: 'Inter' }
      },
    ],
    series: [
      { name: "All Accounts", type: "bar", barGap: 0, label: labelOption, itemStyle: { borderRadius: [4, 4, 0, 0] }, data: [...accountsTotal] },
      { name: "New Accounts", type: "bar", label: labelOption, itemStyle: { borderRadius: [4, 4, 0, 0] }, data: [...accountsNew] },
      { name: "Closed Accounts", type: "bar", label: labelOption, itemStyle: { borderRadius: [4, 4, 0, 0] }, data: [...accountsClosed] },
      { name: "Lost Accounts", type: "bar", label: labelOption, itemStyle: { borderRadius: [4, 4, 0, 0] }, data: [...accountsLost] },
      { name: "Overdue Accounts", type: "bar", label: labelOption, itemStyle: { borderRadius: [4, 4, 0, 0] }, data: [...accountsLost] },
    ],
  };

  option && myChart2.setOption(option);

  // Responsiveness handler
  window.addEventListener("resize", () => {
    myChart1.resize();
    myChart2.resize();
  });
});

// Computed helpers for gamification
const funnelStages = computed(() => {
  if (!props.totals) return [];
  const t = props.totals;
  const total = (t.prospects || 0) + (t.scooping || 0) + (t.open || 0) + (t.closed || 0);
  return [
    { label: 'Prospects', count: t.prospects || 0, color: '#4F46E5', bg: '#EEF2FF', pct: total ? Math.round(((t.prospects||0)/total)*100) : 0 },
    { label: 'Scoping',   count: t.scooping  || 0, color: '#D97706', bg: '#FFFBEB', pct: total ? Math.round(((t.scooping||0)/total)*100)  : 0 },
    { label: 'Evaluation',count: t.open      || 0, color: '#1A56DB', bg: '#EBF0FE', pct: total ? Math.round(((t.open||0)/total)*100)      : 0 },
    { label: 'Closed Won',count: t.closed    || 0, color: '#059669', bg: '#ECFDF5', pct: total ? Math.round(((t.closed||0)/total)*100)    : 0 },
  ];
});

const medals = ['🥇', '🥈', '🥉'];
const podiumOrder = computed(() => {
  if (!props.topusers || props.topusers.length < 2) return props.topusers || [];
  const sorted = [...props.topusers].slice(0, 3);
  if (sorted.length === 3) return [sorted[1], sorted[0], sorted[2]]; // silver, gold, bronze podium
  return sorted;
});

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
         GAMIFIED ENTERPRISE DASHBOARD
         ═══════════════════════════════════════════════════════════ -->
    <div class="space-y-6">

      <!-- ── TOP HERO BANNER ── -->
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#1E3A8A] via-[#1D4ED8] to-[#1e40af] text-white p-6 md:p-8 shadow-lg border border-blue-800">
        <!-- Graphic pattern overlay -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white via-blue-900 to-black pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
          <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10 text-xs font-semibold tracking-wide text-blue-100 mb-3 border border-white/10 backdrop-blur-md">
              <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
              Live Portal
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
              Welcome backs, {{ $page.props.auth.user.first_name }}! 👋
            </h2>
            <p class="text-xs text-blue-200 mt-1.5 font-medium opacity-90">
              Here is what's happening with your accounts and team performance today.
            </p>
          </div>

          <div class="flex items-center gap-6 self-start md:self-auto">
            <div class="text-left">
              <p class="text-[10px] text-blue-200 uppercase tracking-widest font-semibold opacity-75">Team Win Rate</p>
              <p class="text-3xl font-black mt-1">
                {{ totals && totals.closed && (totals.closed + (totals.prospects||0) + (totals.scooping||0)) > 0
                    ? Math.round((totals.closed / ((totals.closed||0)+(totals.prospects||0)+(totals.scooping||0)+(totals.open||0))) * 100)
                    : 0 }}%
              </p>
            </div>
            <div class="w-px h-10 bg-white/20"></div>
            <div class="text-left">
              <p class="text-[10px] text-blue-200 uppercase tracking-widest font-semibold opacity-75">Pipeline Value</p>
              <p class="text-3xl font-black mt-1">KSH {{ totalrevenueforclosedaccounts ? totalrevenueforclosedaccounts.toLocaleString() : '0' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- ── STAT CARDS GRID ── -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total Accounts -->
        <div class="stat-card">
          <div class="flex items-center justify-between mb-3">
            <span class="text-lg">📁</span>
            <span class="text-[0.6875rem] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">All</span>
          </div>
          <p class="text-2xl font-black text-gray-900 leading-none mb-1">{{ accounts }}</p>
          <p class="text-[0.75rem] text-gray-500 font-semibold mb-3">Total Accounts</p>
          <div class="progress-track"><div class="progress-fill" style="width:100%"></div></div>
        </div>

        <!-- Prospects -->
        <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4" class="stat-card">
          <div class="flex items-center justify-between mb-3">
            <span class="text-lg">🔭</span>
            <span class="text-[0.6875rem] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600">Prospects</span>
          </div>
          <p class="text-2xl font-black text-gray-900 leading-none mb-1">{{ totals?.prospects ?? 0 }}</p>
          <p class="text-[0.75rem] text-gray-500 font-semibold mb-3">Stage 1</p>
          <div class="progress-track">
            <div class="progress-fill !bg-indigo-500" :style="`width:${accounts ? Math.min(100, Math.round(((totals?.prospects||0)/accounts)*100)) : 0}%`"></div>
          </div>
        </div>

        <!-- Scoping -->
        <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4" class="stat-card">
          <div class="flex items-center justify-between mb-3">
            <span class="text-lg">🔍</span>
            <span class="text-[0.6875rem] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">Scoping</span>
          </div>
          <p class="text-2xl font-black text-gray-900 leading-none mb-1">{{ totals?.scooping ?? 0 }}</p>
          <p class="text-[0.75rem] text-gray-500 font-semibold mb-3">Stage 2</p>
          <div class="progress-track">
            <div class="progress-fill !bg-amber-500" :style="`width:${accounts ? Math.min(100, Math.round(((totals?.scooping||0)/accounts)*100)) : 0}%`"></div>
          </div>
        </div>

        <!-- Closed Won -->
        <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4" class="stat-card">
          <div class="flex items-center justify-between mb-3">
            <span class="text-lg">🏆</span>
            <span class="text-[0.6875rem] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">Closed Won</span>
          </div>
          <p class="text-2xl font-black text-gray-900 leading-none mb-1">{{ totals?.closed ?? 0 }}</p>
          <p class="text-[0.75rem] text-gray-500 font-semibold mb-3">Wins</p>
          <div class="progress-track">
            <div class="progress-fill !bg-emerald-500" :style="`width:${accounts ? Math.min(100, Math.round(((totals?.closed||0)/accounts)*100)) : 0}%`"></div>
          </div>
        </div>

        <!-- Lost -->
        <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4" class="stat-card">
          <div class="flex items-center justify-between mb-3">
            <span class="text-lg">💨</span>
            <span class="text-[0.6875rem] font-bold px-2 py-0.5 rounded-full bg-red-50 text-red-600">Lost</span>
          </div>
          <p class="text-2xl font-black text-gray-900 leading-none mb-1">{{ totals?.lost ?? 0 }}</p>
          <p class="text-[0.75rem] text-gray-500 font-semibold mb-3">Lost Deals</p>
          <div class="progress-track">
            <div class="progress-fill !bg-red-500" :style="`width:${accounts ? Math.min(100, Math.round(((totals?.lost||0)/accounts)*100)) : 0}%`"></div>
          </div>
        </div>

        <!-- Sales Team -->
        <div class="stat-card">
          <div class="flex items-center justify-between mb-3">
            <span class="text-lg">👥</span>
            <span class="text-[0.6875rem] font-bold px-2 py-0.5 rounded-full bg-purple-50 text-purple-600">Team</span>
          </div>
          <p class="text-2xl font-black text-gray-900 leading-none mb-1">{{ personnels }}</p>
          <p class="text-[0.75rem] text-gray-500 font-semibold mb-3">Sales Reps</p>
          <div class="progress-track"><div class="progress-fill !bg-purple-500" style="width:100%"></div></div>
        </div>
      </div>

      <!-- ── ROW 2: FUNNEL + LEADERBOARD ── -->
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-6" v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4">

        <!-- SALES PIPELINE FUNNEL -->
        <div class="lg:col-span-2 crm-card p-6">
          <div class="flex items-center gap-2.5 mb-6">
            <span class="text-lg">🎯</span>
            <h3 class="font-bold text-gray-900 tracking-tight text-base">Sales Pipeline Funnel</h3>
          </div>

          <div class="space-y-4">
            <div v-for="(stage, i) in funnelStages" :key="stage.label" class="relative">
              <div class="flex items-center gap-3">
                <div class="flex-1" :style="`padding: 0 ${i * 4}%`">
                  <div class="rounded-xl px-4 py-3 flex items-center justify-between transition-all hover:translate-x-1"
                       :style="`background:${stage.bg}; border-left: 4px solid ${stage.color}`">
                    <span class="text-xs font-bold" :style="`color:${stage.color}`">
                      {{ ['🔭','🔍','📊','🏆'][i] }} {{ stage.label }}
                    </span>
                    <div class="text-right">
                      <span class="text-base font-black" :style="`color:${stage.color}`">{{ stage.count }}</span>
                      <span class="text-[10px] ml-1 opacity-70 font-semibold" :style="`color:${stage.color}`">{{ stage.pct }}%</span>
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="i < funnelStages.length - 1" class="flex justify-center py-0.5">
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>
          </div>

          <!-- conversion rate badge -->
          <div class="mt-6 rounded-xl bg-emerald-50/50 border border-emerald-100 px-4 py-3 flex items-center justify-between shadow-xs">
            <span class="text-xs font-bold text-emerald-800">🎉 Conversion Rate (Prospect → Won)</span>
            <span class="text-base font-black text-emerald-800">
              {{ funnelStages[0]?.count ? Math.round(((funnelStages[3]?.count||0)/funnelStages[0].count)*100) : 0 }}%
            </span>
          </div>
        </div>

        <!-- LEADERBOARD -->
        <div class="lg:col-span-3 crm-card p-6">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2.5">
              <span class="text-lg">🏅</span>
              <h3 class="font-bold text-gray-900 tracking-tight text-base">Sales Leaderboard</h3>
            </div>
            <span class="badge badge-neutral">Active Period</span>
          </div>

          <!-- Podium Visual -->
          <div v-if="topusers && topusers.length >= 2" class="flex items-end justify-center gap-4 mb-6 border-b border-gray-100 pb-6">
            <!-- 2nd place -->
            <div v-if="topusers[1]" class="flex flex-col items-center gap-1.5 flex-1 max-w-[100px]">
              <span class="text-xl">🥈</span>
              <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold text-xs ring-2 ring-slate-200 shadow-sm">
                {{ initials(topusers[1]) }}
              </div>
              <p class="text-[10px] font-bold text-center text-slate-700 truncate w-full">{{ topusers[1].first_name }}</p>
              <div class="w-full bg-gradient-to-t from-slate-200 to-slate-100 rounded-t-lg h-14 flex items-end justify-center pb-2.5 shadow-xs">
                <span class="text-slate-600 font-bold text-xs">2</span>
              </div>
            </div>

            <!-- 1st place -->
            <div v-if="topusers[0]" class="flex flex-col items-center gap-1.5 flex-1 max-w-[100px]">
              <span class="text-2xl animate-bounce">🥇</span>
              <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-800 font-extrabold text-sm ring-3 ring-amber-400 ring-offset-1 shadow-md">
                {{ initials(topusers[0]) }}
              </div>
              <p class="text-[10px] font-black text-center text-amber-800 truncate w-full">{{ topusers[0].first_name }}</p>
              <div class="w-full bg-gradient-to-t from-amber-300 to-amber-200 rounded-t-lg h-20 flex items-end justify-center pb-3 shadow-sm">
                <span class="text-amber-900 font-extrabold text-sm">1</span>
              </div>
            </div>

            <!-- 3rd place -->
            <div v-if="topusers[2]" class="flex flex-col items-center gap-1.5 flex-1 max-w-[100px]">
              <span class="text-xl">🥉</span>
              <div class="w-11 h-11 rounded-full bg-orange-50 flex items-center justify-center text-orange-800 font-bold text-xs ring-2 ring-orange-200 shadow-sm">
                {{ initials(topusers[2]) }}
              </div>
              <p class="text-[10px] font-bold text-center text-orange-700 truncate w-full">{{ topusers[2].first_name }}</p>
              <div class="w-full bg-gradient-to-t from-orange-200 to-orange-100 rounded-t-lg h-10 flex items-end justify-center pb-1.5 shadow-xs">
                <span class="text-orange-700 font-bold text-xs">3</span>
              </div>
            </div>
          </div>

          <!-- List View -->
          <div class="space-y-2 max-h-[220px] overflow-y-auto thin-scrollbar">
            <div v-for="(user, idx) in topusers" :key="user.id"
                 class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all border border-transparent hover:border-gray-100 hover:bg-gray-50/50">
              <span class="text-xs font-bold w-5 text-gray-400 text-center">{{ medals[idx] || `#${idx+1}` }}</span>
              <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm ${avatarColor(idx)}`">
                {{ initials(user) }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-gray-800 truncate">{{ user.first_name }} {{ user.last_name }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                  <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-primary transition-all"
                         :style="`width:${topusers[0]?.accounts_count ? Math.round((user.accounts_count/topusers[0].accounts_count)*100) : 0}%`"></div>
                  </div>
                  <span class="text-[10px] text-gray-400 font-semibold">{{ winRate(user) }}% win</span>
                </div>
              </div>
              <div class="text-right flex items-center gap-2">
                <div>
                  <p class="text-xs font-extrabold text-gray-800">{{ user.accounts_count }}</p>
                  <p class="text-[9px] text-gray-400 font-semibold">deals</p>
                </div>
                <div class="flex gap-0.5">
                  <span v-if="user.closed_accounts_count > 0" title="Won" class="text-[10px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded font-bold">✓{{ user.closed_accounts_count }}</span>
                  <span v-if="user.overdue_accounts_count > 0" title="Overdue" class="text-[10px] bg-red-50 text-red-600 px-1.5 py-0.5 rounded font-bold">⚠{{ user.overdue_accounts_count }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── ROW 3: CHARTS SECTION ── -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Account Growth Chart -->
        <div class="crm-card p-6">
          <div class="flex items-center gap-2.5 mb-4">
            <span class="text-lg">📈</span>
            <h3 class="font-bold text-gray-900 tracking-tight text-base">Account Progression</h3>
          </div>
          <div ref="lineChart" style="width:100%;height:300px"></div>
        </div>

        <!-- Revenue Breakdown Chart -->
        <div class="crm-card p-6">
          <div class="flex items-center gap-2.5 mb-4">
            <span class="text-lg">💰</span>
            <h3 class="font-bold text-gray-900 tracking-tight text-base">Monthly Volume Breakdown</h3>
          </div>
          <div ref="pieChart" style="width:100%;height:300px"></div>
        </div>
      </div>

      <!-- ── ROW 4: REVENUE LEADERS + RECENT ACTIONS ── -->
      <div v-if="$page.props.auth.user.role_id === 1 || $page.props.auth.user.role_id === 4"
           class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        <!-- Top Revenue Deals -->
        <div class="lg:col-span-2 crm-card p-6">
          <div class="flex items-center gap-2.5 mb-5">
            <span class="text-lg">💎</span>
            <h3 class="font-bold text-gray-900 tracking-tight text-base">High-Value Leaders</h3>
          </div>

          <div class="space-y-2.5">
            <div v-for="(item, idx) in topsales" :key="item.id"
                 class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 transition-all hover:bg-gray-50/50">
              <span class="text-base">{{ medals[idx] || `•` }}</span>
              <div :class="`w-9 h-9 rounded-lg flex items-center justify-center text-white font-bold text-xs ${avatarColor(idx)} shadow-sm`">
                {{ ((item.first_name?.[0]??'')+(item.last_name?.[0]??'')).toUpperCase() }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 text-xs truncate">{{ item.first_name }} {{ item.last_name }}</p>
                <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ item.clientname || item.email }}</p>
              </div>
              <div class="text-right">
                <p class="font-black text-gray-900 text-xs">KSH {{ Number(item.amount).toLocaleString() }}</p>
                <span class="inline-flex text-[9px] px-1.5 py-0.5 rounded font-bold mt-1"
                      :class="idx===0 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600'">
                  {{ idx===0 ? 'Elite' : 'Active' }}
                </span>
              </div>
            </div>
            <div v-if="!topsales || topsales.length === 0" class="text-center py-10 text-gray-400">
              <p class="text-3xl mb-2">🎯</p>
              <p class="text-xs">No active high-value deals yet.</p>
            </div>
          </div>
        </div>

        <!-- Recent Wins Feed -->
        <div class="lg:col-span-3 crm-card p-6">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2.5">
              <span class="text-lg">⚡</span>
              <h3 class="font-bold text-gray-900 tracking-tight text-base">Recent Won Deals</h3>
            </div>
            <span class="badge badge-success animate-pulse">Live Feed</span>
          </div>

          <div class="space-y-2.5">
            <div v-for="(item, idx) in recentsales" :key="item.id"
                 class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/50 border border-gray-100/50 hover:bg-blue-50/40 hover:border-blue-100 transition-all group">
              <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary/10 to-indigo-600/10 text-primary flex items-center justify-center text-xs font-bold flex-shrink-0">
                {{ idx+1 }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 text-xs truncate group-hover:text-primary transition-colors">{{ item.business_name || item.name }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5 font-medium">
                  {{ item.first_name }} {{ item.last_name }} · {{ new Date(item.date).toLocaleDateString('en-US',{month:'short',day:'numeric'}) }}
                </p>
              </div>
              <div class="text-right flex-shrink-0 mr-1">
                <p class="font-black text-emerald-600 text-xs">KSH {{ Number(item.amount).toLocaleString() }}</p>
              </div>
              <Link :href="`/accounts/account/${item.account_id}`"
                    class="opacity-0 group-hover:opacity-100 transition-opacity text-xs text-primary font-semibold">
                →
              </Link>
            </div>
            <div v-if="!recentsales || recentsales.length === 0" class="text-center py-10 text-gray-400">
              <p class="text-3xl mb-2">💤</p>
              <p class="text-xs">No wins recorded in this sequence.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- ── ROW 5: FULL TEAM SCORECARD ── -->
      <div v-if="$page.props.auth.user.role_id === 1" class="crm-card p-6">
        <div class="flex items-center gap-2.5 mb-5">
          <span class="text-lg">📊</span>
          <h3 class="font-bold text-gray-900 tracking-tight text-base">Full Team Scorecard</h3>
        </div>

        <div class="overflow-x-auto thin-scrollbar">
          <table class="min-w-full text-xs text-left">
            <thead>
              <tr class="border-b border-gray-200/80 text-gray-500 uppercase font-bold tracking-wider">
                <th class="py-3 px-4 w-12">Rank</th>
                <th class="py-3 px-4">Sales Rep</th>
                <th class="py-3 px-4 text-right">Total Accounts</th>
                <th class="py-3 px-4 text-right">New</th>
                <th class="py-3 px-4 text-right">Won 🏆</th>
                <th class="py-3 px-4 text-right">Lost 💨</th>
                <th class="py-3 px-4 text-right">Overdue ⚠</th>
                <th class="py-3 px-4 text-right">Open</th>
                <th class="py-3 px-4 text-right">Win %</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="(user, idx) in topusers" :key="user.id"
                  class="hover:bg-gray-50/50 transition-colors"
                  :class="idx===0 ? 'bg-amber-50/10' : ''">
                <td class="py-3.5 px-4 font-extrabold text-gray-400">
                  {{ medals[idx] || `#${idx+1}` }}
                </td>
                <td class="py-3.5 px-4">
                  <div class="flex items-center gap-2.5">
                    <div :class="`w-7 h-7 rounded-lg ${avatarColor(idx)} flex items-center justify-center text-white text-[10px] font-bold shadow-xs`">
                      {{ initials(user) }}
                    </div>
                    <div>
                      <span class="font-bold text-gray-900">{{ user.first_name }} {{ user.last_name }}</span>
                      <span v-if="idx===0" class="text-[9px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-extrabold ml-1.5">MVP</span>
                    </div>
                  </div>
                </td>
                <td class="py-3.5 px-4 text-right font-bold text-gray-800">{{ user.accounts_count }}</td>
                <td class="py-3.5 px-4 text-right text-blue-600 font-semibold">{{ user.new_accounts_count }}</td>
                <td class="py-3.5 px-4 text-right text-emerald-600 font-bold">{{ user.closed_accounts_count }}</td>
                <td class="py-3.5 px-4 text-right text-red-500">{{ user.lost_accounts_count }}</td>
                <td class="py-3.5 px-4 text-right text-amber-600 font-semibold">{{ user.overdue_accounts_count }}</td>
                <td class="py-3.5 px-4 text-right text-gray-500">{{ user.open_accounts_count }}</td>
                <td class="py-3.5 px-4 text-right">
                  <span class="font-extrabold px-2.5 py-1 rounded-full text-[10px]"
                        :class="winRate(user)>=50 ? 'bg-emerald-50 text-emerald-700' : winRate(user)>=25 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700'">
                    {{ winRate(user) }}%
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </AppLayout>
</template>
