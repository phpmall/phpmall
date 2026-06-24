import { Routes, Route, Navigate } from "react-router-dom";
import AdminLayout from "./layouts/AdminLayout";
import Dashboard from "./pages/Dashboard";
import MerchantList from "./pages/merchant/MerchantList";
import MerchantAudit from "./pages/merchant/MerchantAudit";
import MerchantSettlement from "./pages/merchant/MerchantSettlement";
import ProductList from "./pages/product/ProductList";
import ProductCategory from "./pages/product/ProductCategory";
import ProductBrand from "./pages/product/ProductBrand";
import OrderList from "./pages/order/OrderList";
import OrderRefund from "./pages/order/OrderRefund";
import OrderDelivery from "./pages/order/OrderDelivery";
import MarketingCoupon from "./pages/marketing/MarketingCoupon";
import MarketingSeckill from "./pages/marketing/MarketingSeckill";
import MarketingDiscount from "./pages/marketing/MarketingDiscount";
import DistributorList from "./pages/distribution/DistributorList";
import CommissionList from "./pages/distribution/CommissionList";
import FinanceOverview from "./pages/finance/FinanceOverview";
import FinanceReconciliation from "./pages/finance/FinanceReconciliation";
import FinanceWithdraw from "./pages/finance/FinanceWithdraw";
import ContentBanner from "./pages/content/ContentBanner";
import ContentArticle from "./pages/content/ContentArticle";
import ContentNotice from "./pages/content/ContentNotice";
import SystemUser from "./pages/system/SystemUser";
import SystemRole from "./pages/system/SystemRole";
import SystemConfig from "./pages/system/SystemConfig";
import SystemLog from "./pages/system/SystemLog";

function App() {
  return (
    <Routes>
      <Route path="/" element={<AdminLayout />}>
        <Route index element={<Navigate to="/dashboard" replace />} />
        <Route path="dashboard" element={<Dashboard />} />
        <Route path="merchant/list" element={<MerchantList />} />
        <Route path="merchant/audit" element={<MerchantAudit />} />
        <Route path="merchant/settlement" element={<MerchantSettlement />} />
        <Route path="product/list" element={<ProductList />} />
        <Route path="product/category" element={<ProductCategory />} />
        <Route path="product/brand" element={<ProductBrand />} />
        <Route path="order/list" element={<OrderList />} />
        <Route path="order/refund" element={<OrderRefund />} />
        <Route path="order/delivery" element={<OrderDelivery />} />
        <Route path="marketing/coupon" element={<MarketingCoupon />} />
        <Route path="marketing/seckill" element={<MarketingSeckill />} />
        <Route path="marketing/discount" element={<MarketingDiscount />} />
        <Route path="distribution/distributor" element={<DistributorList />} />
        <Route path="distribution/commission" element={<CommissionList />} />
        <Route path="finance/overview" element={<FinanceOverview />} />
        <Route path="finance/reconciliation" element={<FinanceReconciliation />} />
        <Route path="finance/withdraw" element={<FinanceWithdraw />} />
        <Route path="content/banner" element={<ContentBanner />} />
        <Route path="content/article" element={<ContentArticle />} />
        <Route path="content/notice" element={<ContentNotice />} />
        <Route path="system/user" element={<SystemUser />} />
        <Route path="system/role" element={<SystemRole />} />
        <Route path="system/config" element={<SystemConfig />} />
        <Route path="system/log" element={<SystemLog />} />
      </Route>
    </Routes>
  );
}

export default App;
