import { usePage, Link } from "@inertiajs/react";
import ReactMarkdown from "react-markdown";
import remarkGfm from "remark-gfm";

import { Tag } from "../components";
import { Container, Time } from "../../../shared";

export default function PostDetail({ background, indexItem }) {
    const data = usePage().props.components[indexItem];
    return (
        <div
            className="detailPost backgroundImg"
            id={data?.id}
            style={{
                backgroundImage: `url(${background?.data})` || null,
                backgroundColor: background?.data || "transparent",
            }}
        >
            <Container>
                {data.cover && (
                    <img
                        className="detailPost--img w-full h-[450px]"
                        src={data.cover}
                        alt="cover"
                    />
                )}
                <div className="detailPost__container--authorTag">
                    <div className="detailPost__container--author">
                        {data.author.thumbnail && (
                            <img
                                className="detailPost__container--authorImg"
                                src={data.author.thumbnail}
                            />
                        )}
                        <h4 className="detailPost__container--authorName">
                            {data.author.name}
                        </h4>
                        <Time postDetail={true} date={data.posted_at} />
                    </div>
                    <div>
                        {data.tags.map((tag, index) => {
                            return (
                                <Link key={tag.title + index} href={tag.url}>
                                    <Tag
                                        color={tag.color || "#4C94AF"}
                                        title={tag.title}
                                        className="mb-2"
                                    />
                                </Link>
                            );
                        })}
                    </div>
                </div>
                <h2 className="detailPost--title">{data.title}</h2>
                <div className="cssMarkdownPostDetail markdown" dangerouslySetInnerHTML={{__html: data.content}}/>
            </Container>
        </div>
    );
}
