import { Link, usePage } from "@inertiajs/react";
import clsx from "clsx";
import React from "react";

export default function NavGroup({ background, indexItem}) {

    const config = usePage().props.footer.components[indexItem]

    const { title, navs, url  } = config
  const textColor = () => {
    // if (theme === "light-blue") return "text-white";
    // return "text-[#227C9D]";
  };

  return (
    <div className="navgroup">
      <a
        href={url}
        target="_blank"
        rel="noreferrer"
        className={clsx(textColor(), "navgroup--title")}
      >
        {title}
      </a>
      <ul>
        {navs.map((nav, index) => (
          <li key={nav.name + index} className="block">
            {nav.url?.startsWith("https") &&
              <a
                href={nav.url}
                className={clsx(
                  textColor(),
                  "navgroup--name"
                )}
                target="_blank"
              >
                {nav.name}
              </a>
            }
            {nav.url?.startsWith("/") &&
              <Link
                href={nav.url}
                className={clsx(
                  textColor(),
                  "navgroup--name"
                )}
              >
                {nav.name}
              </Link>
            }

            {!nav.url?.startsWith("/") && !nav.url?.startsWith("https") &&
              <a
                href={"#" + nav.url}
                className={clsx(
                  textColor(),
                  "navgroup--name"
                )}
              >
                {nav.name}
              </a>
            }
          </li>
        ))}
      </ul>
    </div>
  );
}
