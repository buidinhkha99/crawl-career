import React, { useState } from "react";
import { Col, Row, Pagination } from "antd";
import { usePage, router } from "@inertiajs/react";

import { Container } from "../../../shared";
import { CardPostRound, CardPostSquare } from "../components";

export default function Post({ background, indexItem }) {
    const data = usePage().props.components[indexItem];
    const { url } = usePage();
    const path = url.split("?")[0];
    const [currentPage, setCurrenPage] = useState(data.current_page);
    const [listPost, setListPost] = useState(data.list_card);

    const handleChange = (pageNum, pageSize) => {
        router.visit(`${path}?page=${pageNum}`, {
            only: [],
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                const paginator = page.props.components[indexItem];
                setCurrenPage(paginator.current_page);
                setListPost(paginator.list_card);
                window.scrollTo(0, 0);
            },
        });
    };

    if (listPost.length <= 0) {
        // @TODO fill some empty content here
        return (
            <div></div>
        );
    }

    return (
        <div
            className="post backgroundImg"
            id={data?.id}
            style={{
                backgroundImage: `url(${background?.data})` || null,
                backgroundColor: background?.data || "transparent",
            }}
        >
            <Container>
                <div className="post--listCard">
                    <Row gutter={[0, 30]}>
                        {listPost.map((card, index) => (
                            <Col
                                key={index + card.title + card.description}
                                span={24}
                            >
                                {data.type_card === "img_round" ? (
                                    <CardPostRound
                                        img={card.img}
                                        title={card.title}
                                        date={card.date}
                                        description={card.description}
                                        tags={card.tags}
                                        url={card.url}
                                    />
                                ) : (
                                    <CardPostSquare
                                        img={card.img}
                                        title={card.title}
                                        date={card.date}
                                        description={card.description}
                                        url={card.url}
                                        tags={card.tags}
                                    />
                                )}
                            </Col>
                        ))}
                    </Row>
                </div>

                {data.total > data.per_page &&
                    <div className="theme-light-blue-round">
                        <Pagination
                            showSizeChanger={false}
                            pageSize={data.per_page}
                            current={currentPage}
                            total={data.total}
                            onChange={handleChange}
                            className="paginationRound"
                        />
                    </div>
                }
            </Container>
        </div>
    );
}
