import { lazy } from "react";
import { Link, usePage } from "@inertiajs/react";

const Logo = lazy(() => import("../../../shared/components/Logo/Logo"));

export default function Info({ background, indexItem }) {
    const config = usePage().props.footer.components[indexItem];
    const { logo, text, socials } = config;

    return (
        <section className="flex flex-col item-center">
            {logo && <Logo logo={logo} />}
            <h2 className="mb-2 text-center xxl:px-7 px-0">{text}</h2>
            <ul className="flex justify-center items-center">
                {socials?.length > 0 &&
                    socials?.map((item, key) => (
                        <li key={`${item.data} + ${key++}`} className="mr-2">
                            {item.url?.startsWith("https") && (
                                <a
                                    className="inline-block w-4"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    href={item.url}
                                >
                                    <div
                                        dangerouslySetInnerHTML={{
                                            __html: item.data,
                                        }}
                                    />
                                </a>
                            )}
                            {item.url?.startsWith("/") && (
                                <Link
                                    className="inline-block w-4"
                                    href={item.url}
                                >
                                    <div
                                        dangerouslySetInnerHTML={{
                                            __html: item.data,
                                        }}
                                    />
                                </Link>
                            )}
                            {item.url?.startsWith("#") && item.url && (
                                <a className="inline-block w-4" href={item.url}>
                                    <div
                                        dangerouslySetInnerHTML={{
                                            __html: item.data,
                                        }}
                                    />
                                </a>
                            )}
                            {!item.url && item.data && (
                                <div
                                    dangerouslySetInnerHTML={{
                                        __html: item.data,
                                    }}
                                    className="inline-block w-4"
                                />
                            )}
                        </li>
                    ))}
            </ul>
        </section>
    );
}
