import { j as jsx } from "../ssr.mjs";
import { Layout } from "antd";
import "react/jsx-runtime";
import "react-dom/server";
import "@inertiajs/inertia-react";
import "process";
import "http";
const { Footer: FooterAntd } = Layout;
const Footer = () => {
  return /* @__PURE__ */ jsx(
    FooterAntd,
    {
      style: {
        textAlign: "center"
      },
      children: "Ant Design ©2018 Created by Ant UED"
    }
  );
};
export {
  Footer as default
};
