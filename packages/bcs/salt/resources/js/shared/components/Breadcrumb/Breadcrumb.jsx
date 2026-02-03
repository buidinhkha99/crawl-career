import React from "react";
import { Link } from "@inertiajs/react";
import { Breadcrumb as AntBreadcrumb } from "antd";
import { Container } from "../../Container";

export default function Breadcrumb() {
    const breadCrumbView = () => {
        const { pathname } = window.location;
        const pathnames = pathname.split("/").filter((item) => item);

        const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);

        const removeHypen = (s) =>
            (s = s
                .split("-")
                .map((item) => capitalize(item))
                .join(" "));

        const removeQueryString = (s) => (s = s.slice(0, s.indexOf("?")));

        const handlePath = (path) => {
            // Check path if it contains query string or not
            path = path.includes("?") ? removeQueryString(path) : path;

            // Check path if it contains hyphen - or not
            path = path.includes("-") ? removeHypen(path) : capitalize(path);

            return path;
        };

        return (
            pathnames.length > 2 && (
                <div className="breadcrumb__container">
                    <Container>
                        <AntBreadcrumb separator="•">
                            {pathnames.map((name, index) => {
                                const routeTo = `/${pathnames
                                    .slice(0, index + 1)
                                    .join("/")}`;
                                const isLast = index === pathnames.length - 1;
                                return isLast ? (
                                    <AntBreadcrumb.Item key={name + index}>
                                        <span className="breadcrumb__item--active">
                                            {handlePath(name)}
                                        </span>
                                    </AntBreadcrumb.Item>
                                ) : (
                                    <AntBreadcrumb.Item key={name + index}>
                                        <Link
                                            href={`${routeTo}`}
                                            className="breadcrumb__item"
                                        >
                                            {handlePath(name)}
                                        </Link>
                                    </AntBreadcrumb.Item>
                                );
                            })}
                        </AntBreadcrumb>
                    </Container>
                </div>
            )
        );
    };

    return <div>{breadCrumbView()}</div>;
}
