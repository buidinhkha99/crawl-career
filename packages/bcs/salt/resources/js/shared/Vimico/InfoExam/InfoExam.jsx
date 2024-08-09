import { Suspense } from "react";
import { Col, Row } from "antd";
import dayjs from "dayjs";

import { Container } from "../../Container";
import { Loading } from "../../components";
import { moockApi } from "./InfoExam.constants";
import { CandidateInfo } from "../components";
import { usePage } from "@inertiajs/react";
import logoVimico from "../../../../img/logoVimico.png";

export default function InfoExam({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<Loading />}>
            <div
                className="py-5 backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <Row
                        gutter={{
                            xl: 60
                        }}
                        className="flex justify-center items-center"
                    >
                        <Col xs={24} lg={16}>
                            <CandidateInfo
                                avatar={config.avatar}
                                full_name={config.full_name}
                                identification_number={config.identification_number}
                                date_of_birth={config.date_of_birth}
                                coaching_team={config.coaching_team}
                                work_unit={config.work_unit}
                                working_position={
                                    config.working_position
                                }
                                className=""
                            />
                        </Col>
                        <Col xs={24} lg={8}>
                            <div className="flex flex-col gap-10">
                                <div className="flex flex-col gap-[10px] pl-5 border border-transparent border-l-[#324376]">
                                    <span className="text-[14px] text-[#324376] font-bold">
                                        {config.test_time.title}
                                    </span>
                                    <span className="text-[14px] text-black font-semibold">
                                        {config.test_time.examinations}
                                    </span>
                                    <span className="font-normal text-[14px] text-black">
                                        Thời gian:{" "}
                                        {dayjs(
                                            config.test_time.start_at
                                        ).format("DD/MM/YYYY")}{" "}
                                        -{" "}
                                        {dayjs(
                                            config.test_time.end_at
                                        ).format("DD/MM/YYYY")}
                                    </span>
                                </div>
                                <div className="flex items-center gap-1 pl-5 border border-transparent border-l-[#324376]">
                                    <div className="flex flex-col gap-[10px]">
                                        <span className="text-[14px] text-[#324376] font-bold">
                                            {config.info_company.title}
                                        </span>
                                        <span className="font-normal text-[14px] text-black">
                                            Địa chỉ:{" "}
                                            {config.info_company.address}
                                        </span>
                                        <span className="font-normal text-[14px] text-black">
                                            Điện thoại:{" "}
                                            {config.info_company.phone}
                                        </span>
                                        <span className="font-normal text-[14px] text-black">
                                            Website:{" "}
                                            <a
                                                href={
                                                    config.info_company
                                                        .website
                                                }
                                                target="_blank"
                                                className="text-[#586BA4] font-bold"
                                            >
                                                {config.info_company.website}
                                            </a>
                                        </span>
                                    </div>
                                    <img
                                        src={logoVimico}
                                        alt="img logo"
                                        className="max-w-[165px] max-h-[120px]"
                                    />
                                </div>
                            </div>
                        </Col>
                    </Row>
                </Container>
            </div>
        </Suspense>
    );
}
