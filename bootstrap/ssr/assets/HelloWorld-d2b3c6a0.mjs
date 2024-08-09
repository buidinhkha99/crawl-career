import { j as jsx } from "../ssr.mjs";
import "@inertiajs/inertia-react";
import "react/jsx-runtime";
import "react-dom/server";
import "process";
import "http";
const HelloWorld = () => {
  return /* @__PURE__ */ jsx("h1", { className: "text-3xl font-bold underline", children: "Hello world!" });
};
export {
  HelloWorld as default
};
